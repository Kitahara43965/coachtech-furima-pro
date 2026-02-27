<?php

namespace App\Services;

use App\Constants\ShownItemsKind;
use App\Models\User;
use App\Models\UserItem;
use App\Models\Item;
use App\Services\TransactionCommentService;
use App\Constants\UserItemStatus;
use App\Constants\TransactionCommentStatus;

final class TradingItemSwapKind{
    public const UNDEFINED = 0;
    public const SWAP = 1;
    public const NO_SWAP = 2;
    public const MAX = 2;
}

class TradingItemsService{
    public static function getTradingItemsCollectionFromUserIdAndKeyword(
        $userId,
        $keyword = null
    ){
        $user = User::find($userId);
        $tradingItems = Item::query()->getModel()->newCollection();
        $query = Item::where(function ($q) use($userId) {
            $q->whereHas('purchasedByUsers', function ($q2) use($userId) {
                    $q2->where('users.id', $userId)
                    ->where('user_item.type', 'purchase')
                    ->where('user_item.status', UserItemStatus::TRADING)
                    ->where('user_item.purchase_quantity', '>=', 1);
                })
                ->orWhereHas('usersByOwnership', function ($q2) use($userId) {
                    $q2->where('users.id', $userId);
                });
        })
        ->whereHas('purchasedByUsers', function ($q) {
            $q->where('user_item.type', 'purchase')
            ->where('user_item.status', UserItemStatus::TRADING)
            ->where('user_item.purchase_quantity', '>=', 1);
        });
        if (!empty($keyword)) {
            $query = $query->where('name', 'like', '%' . $keyword . '%');
        }
        $tradingItems = $query->get();

        $maxTradingItemNumber = 0;
        if(!$tradingItems->isEmpty()){
            $maxTradingItemNumber = $tradingItems->count();
        }

        $noSwapLatestCreatedAts = null;
        $noSwapTradingItemsArray = null;
        $swapLatestCreatedAts = null;
        $swapTradingItemsArray = null;

        for($loopTime = 1; $loopTime <= 2; $loopTime++){
            $noSwapTradingItemNumber = 0;
            $swapTradingItemNumber = 0;
            $maxNoSwapTradingItemNumber = 0;
            $maxSwapTradingItemNumber = 0;

            for($tradingItemNumber = 1;$tradingItemNumber <= $maxTradingItemNumber;$tradingItemNumber++){
                $tradingItem = $tradingItems->get($tradingItemNumber - 1);
                $tradingItemId = $tradingItem ? $tradingItem->id : null;
                $tradingUserItemId
                    = TransactionCommentService::getPurchasedUserItemIdFromItemIdAndUserId(
                        $tradingItemId,
                        $userId
                    );
                $tradingUserItem = UserItem::find($tradingUserItemId);
                $latestCreatedAt = null;
                if($tradingUserItem){
                    $tradingTransactionCommentsQuery = $tradingUserItem->transactionComments();
                    $tradingTransactionComments = $tradingTransactionCommentsQuery->get();
                    
                    if($tradingTransactionComments && !$tradingTransactionComments->isEmpty()){
                        $latestTransactionComment = $tradingTransactionCommentsQuery
                            ->where('status', TransactionCommentStatus::PUBLISHED)
                            ->where('user_id', '!=', $userId)
                            ->orderBy('created_at', 'desc')
                            ->first();
                        if($latestTransactionComment){
                            $latestCreatedAt = $latestTransactionComment->created_at;
                        }//$latestTransactionComment
                    }//$tradingTransactionComments
                }//$tradingUserItem

                if($latestCreatedAt){
                    $swapTradingItemNumber = $swapTradingItemNumber + 1;
                    if($loopTime === 2){
                        $swapLatestCreatedAts[$swapTradingItemNumber - 1] = $latestCreatedAt;
                        $swapTradingItemsArray[$swapTradingItemNumber - 1] = $tradingItem;
                    }//$loopTime
                }else{//$latestCreatedAt
                    $noSwapTradingItemNumber = $noSwapTradingItemNumber + 1;
                    if($loopTime === 2){
                        $noSwapLatestCreatedAts[$noSwapTradingItemNumber - 1] = $latestCreatedAt;
                        $noSwapTradingItemsArray[$noSwapTradingItemNumber - 1] = $tradingItem;
                    }//$loopTime
                }//$latestCreatedAt
            }

            if($loopTime === 1){
                if($swapTradingItemNumber >= 1){
                    $swapLatestCreatedAts = array_fill(0,$swapTradingItemNumber,null);
                    $swapTradingItemsArray = array_fill(0,$swapTradingItemNumber,null);
                }//$swapTradingItemNumber
                if($noSwapTradingItemNumber >= 1){
                    $noSwapLatestCreatedAts = array_fill(0,$noSwapTradingItemNumber,null);
                    $noSwapTradingItemsArray = array_fill(0,$noSwapTradingItemNumber,null);
                }//$noSwapTradingItemNumber
            }//$loopTime

            $maxSwapTradingItemNumber = $swapTradingItemNumber;
            $maxNoSwapTradingItemNumber = $noSwapTradingItemNumber;

        }//$loopTime

        for(
            $tradingItemNumber=1;
            $tradingItemNumber<=$maxSwapTradingItemNumber;
            $tradingItemNumber++
        ){
            for(
                $refTradingItemNumber=$tradingItemNumber;
                $refTradingItemNumber<=$maxSwapTradingItemNumber;
                $refTradingItemNumber++
            ){
                $latestCreatedAt = $swapLatestCreatedAts[$tradingItemNumber - 1];
                $tradingItem = $swapTradingItemsArray[$tradingItemNumber - 1];

                $refLatestCreatedAt = $swapLatestCreatedAts[$refTradingItemNumber - 1];
                $refTradingItem = $swapTradingItemsArray[$refTradingItemNumber - 1];

                $swapMarker = 0;
                if ($latestCreatedAt && $refLatestCreatedAt) {
                    if ($latestCreatedAt->gt($refLatestCreatedAt)) {
                        $swapMarker = 2;
                    }
                }

                if($swapMarker !== 0){
                    $dummyLatestCreatedAt = null;
                    $dummyTradingItem = null;
                    $dummyLatestCreatedAt = $latestCreatedAt;
                    $dummyTradingItem = $tradingItem;

                    $latestCreatedAt = null;
                    $tradingItem = null;
                    $latestCreatedAt = $refLatestCreatedAt;
                    $tradingItem = $refTradingItem;

                    $refLatestCreatedAt = null;
                    $refTradingItem = null;
                    $refLatestCreatedAt = $dummyLatestCreatedAt;
                    $refTradingItem = $dummyTradingItem;
                }//$swapMarker&0

                $swapLatestCreatedAts[$tradingItemNumber - 1] = $refLatestCreatedAt;
                $swapTradingItemsArray[$tradingItemNumber - 1] = $refTradingItem;
                $swapLatestCreatedAts[$refTradingItemNumber - 1] = $latestCreatedAt;
                $swapTradingItemsArray[$refTradingItemNumber - 1] = $tradingItem;
            }
        }

        $latestCreatedAts = null;
        $tradingItemsArray = null;
        if($maxTradingItemNumber){
            $latestCreatedAts = array_fill(0,$maxTradingItemNumber,null);
            $tradingItemsArray = array_fill(0,$maxTradingItemNumber,null);
        }

        $totalTradingItemNumber = 0;
        for($tradingItemKind = 1; $tradingItemKind <= TradingItemSwapKind::MAX; $tradingItemKind++){
            $maxTradingItemNumber = 0;
            if($tradingItemKind === TradingItemSwapKind::SWAP){
                $maxTradingItemNumber = $maxSwapTradingItemNumber;
            }else if($tradingItemKind === TradingItemSwapKind::NO_SWAP){
                $maxTradingItemNumber = $maxNoSwapTradingItemNumber;
            }//$tradingItemKind
            for($tradingItemNumber = 1; $tradingItemNumber<=$maxTradingItemNumber ;$tradingItemNumber++){
                $wholeTradingItemNumber = $totalTradingItemNumber + $tradingItemNumber;
                $latestCreatedAt = null;
                $tradingItem = null;
                if($tradingItemKind === TradingItemSwapKind::SWAP){
                    $latestCreatedAt = $swapLatestCreatedAts[$tradingItemNumber - 1];
                    $tradingItem = $swapTradingItemsArray[$tradingItemNumber - 1];
                }else if($tradingItemKind === TradingItemSwapKind::NO_SWAP){
                    $latestCreatedAt = $noSwapLatestCreatedAts[$tradingItemNumber - 1];
                    $tradingItem = $noSwapTradingItemsArray[$tradingItemNumber - 1];
                }//$tradingItemKind
                $latestCreatedAts[$wholeTradingItemNumber - 1] = $latestCreatedAt;
                $tradingItemsArray[$wholeTradingItemNumber - 1] = $tradingItem;
            }
            $totalTradingItemNumber = $totalTradingItemNumber + $maxTradingItemNumber;
        }//$tradingItemKind

        $tradingItems = Item::query()->getModel()->newCollection();
        if($tradingItemsArray){
            $tradingItems = collect($tradingItemsArray);
        }//$tradingItemsArray

        return($tradingItems);
    }
}