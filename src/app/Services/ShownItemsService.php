<?php

namespace App\Services;

use App\Constants\ShownItemsKind;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Services\TradingItemsService;

class ShownItemsService{
    public static function getShownItems($user,$keyword,$shownItemsKind){

        $query = null;
        $shownItems = Item::query()->getModel()->newCollection();
        $userId = $user? $user->id : null;

        if ($shownItemsKind == ShownItemsKind::OTHER_USERS_GOODS) {
            $query = Item::with([
                'condition',
                'categories',
                'favoritedByUsers',
                'purchasedByUsers',
                'comments.user',
            ]);
            if ($user) {
                $query = $query->where(function ($q) use ($user) {
                    $q->whereHas('usersByOwnership', function ($q2) use ($user) {
                        $q2->where('user_id', '!=', $user->id);
                    })
                    ->orDoesntHave('usersByOwnership');
                });
            }
            if (!empty($keyword)) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }
            $shownItems = $query->get();

        }else if ($shownItemsKind == ShownItemsKind::FAVORITE_GOODS) {
            if ($user) {
                $query = $user->favoriteItems();
            }
            if (!empty($keyword)) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }
            $shownItems = $query->get();
        }else if ($shownItemsKind == ShownItemsKind::SOLD_GOODS) {
            if ($user) {
                $query = $user->ownedItems();
            }
            if (!empty($keyword)) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }
            $shownItems = $query->get();
        }else if ($shownItemsKind == ShownItemsKind::BOUGHT_GOODS) {
            if ($user) {
                $query = $user->purchasedAndCompletedItems();
            }
            if (!empty($keyword)) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }
            $shownItems = $query->get();
        }else if ($shownItemsKind == ShownItemsKind::DEAL_GOODS) {
            
            $shownItems = TradingItemsService::getTradingItemsCollectionFromUserIdAndKeyword(
                $userId,
                $keyword
            );

        }//$indexKind
        
        return($shownItems);
    }
}