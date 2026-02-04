<?php

namespace App\Services;

use App\Constants\ShownItemsKind;
use App\Models\User;
use App\Models\Item;

class ShownItemsService{
    public static function getShownItems($user,$keyword,$shownItemsKind){

        $shownItemsQuery = null;
        $shownItems = null;

        if ($shownItemsKind == ShownItemsKind::OTHER_USERS_GOODS) {
            $shownItemsQuery = Item::with([
                'condition',
                'categories',
                'favoritedByUsers',
                'purchasedByUsers',
                'comments.user',
            ]);
            if ($user) {
                $shownItemsQuery = $shownItemsQuery->where(function ($shownItemsQuery) use ($user) {
                    $shownItemsQuery->whereHas('usersByOwnership', function ($q) use ($user) {
                        $q->where('user_id', '!=', $user->id);
                    })
                    ->orDoesntHave('usersByOwnership');
                });
            }
        }else if ($shownItemsKind == ShownItemsKind::FAVORITE_GOODS) {
            if ($user) {
                $shownItemsQuery = $user->favoriteItems();
            }
        }else if ($shownItemsKind == ShownItemsKind::SOLD_GOODS) {
            if ($user) {
                $shownItemsQuery = $user->ownedItems();
            }
        }else if ($shownItemsKind == ShownItemsKind::BOUGHT_GOODS) {
            if ($user) {
                $shownItemsQuery = $user->purchasedAndCompletedItems();
            }
        }else if ($shownItemsKind == ShownItemsKind::DEAL_GOODS) {
            if ($user) {
                $shownItemsQuery = $user->tradingItems();
            }
        }//$indexKind

        
        if($shownItemsQuery){
            if (!empty($keyword)) {
                $shownItemsQuery->where('name', 'like', '%' . $keyword . '%');
            }
            $shownItems = $shownItemsQuery->get();
        }//$shownItemsQuery
        
        return($shownItems);
    }
}