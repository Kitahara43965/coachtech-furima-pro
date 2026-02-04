<?php

namespace App\Services;

use App\Models\Item;
use App\Models\UserItem;
use App\Models\TransactionComment;
use App\Constants\TransactionCommentStatus;
use Illuminate\Support\Facades\Auth;

class TransactionCommentService{


    public static function getUserItemIdForBothUsersFromUserIdAndItemId(
        $userId,
        $itemId
    ){
        $item = Item::find($itemId);
        $buyerId = null;
        $sellerId = null;
        if($item){
            $seller = $item->seller();
            $buyer = $item->buyer();
            $buyerId = $buyer ? $buyer->id : null;
            $seller = $item->seller();
            $sellerId = $seller? $seller->id : null;
        }//$item

        $counterpartUserId = null;
        if($userId){
            if($userId === $sellerId){
                $counterpartUserId = $buyerId;
            }else if($userId === $buyerId){
                $counterpartUserId = $sellerId;
            }//$userId
        }//$userId

        $userItem = null;
        if($userId&&$counterpartUserId&&$itemId){
            $userItem = UserItem::where(function ($query) use ($userId, $counterpartUserId, $itemId) {
                    $query->where(function ($q) use ($userId, $itemId) {
                        $q->where('user_id', $userId)
                        ->where('item_id', $itemId);
                    })
                    ->orWhere(function ($q) use ($counterpartUserId, $itemId) {
                        $q->where('user_id', $counterpartUserId)
                        ->where('item_id', $itemId);
                    });
                })
                ->where('type', 'purchase')
                ->first();
        }//

        $userItemId = $userItem ? $userItem->id : null;

        return($userItemId);
    }

    public static function getDraftTransactionCommentFromUserIdAndItemId(
        $userId,
        $itemId
    ) {
        $userItemId = self::getUserItemIdForBothUsersFromUserIdAndItemId(
            $userId,
            $itemId
        );

        $userItem = UserItem::find($userItemId);

        $draftTransactionComment = null;
        if($userItem){
            $draftTransactionComment = $userItem->transactionComments()
                                ->where('user_id', $userId)
                                ->where('status', TransactionCommentStatus::DRAFT)
                                ->orderBy('created_at', 'asc')
                                ->first();
        }//$userItem

        return $draftTransactionComment;
    }
    
    public static function getPublishedTransactionCommentsFromUserIdAndItemId(
        $userId,
        $itemId
    ) {
        $userItemId = self::getUserItemIdForBothUsersFromUserIdAndItemId(
            $userId,
            $itemId
        );

        $userItem = UserItem::find($userItemId);

        $publishedTransactionComments = null;
        if($userItem){
            $publishedTransactionComments = $userItem->transactionComments()
                                ->where('status', TransactionCommentStatus::PUBLISHED)
                                ->orderBy('created_at', 'asc')
                                ->get();
        }//$userItem

        return $publishedTransactionComments;
    }

    
}