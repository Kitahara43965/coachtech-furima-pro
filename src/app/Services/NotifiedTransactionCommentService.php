<?php

namespace App\Services;
use App\Models\TransactionComment;
use App\Constants\TransactionCommentStatus;

class NotifiedTransactionCommentService{
    public static function getNotifiedTransactionCommentsFromItemIdAndUserId(
        $itemId,
        $userId
    ){
        $userItemId
            = TransactionCommentService::getPurchasedUserItemIdFromItemIdAndUserId(
                $itemId,
                $userId
            );
        
        $transactionComments = TransactionComment::where('user_item_id',$userItemId)
                            ->where('user_id','!=',$userId)
                            ->where('status',TransactionCommentStatus::PUBLISHED)
                            ->where('is_watched',false)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return($transactionComments);
    }

    public static function getNotifiedPropertiesFromItemsAndUserId(
        $items,
        $userId
    ){
        $maxItemNumber = 0;
        if($items && !$items->isEmpty()){
            $maxItemNumber = $items->count();
        }

        $maxNotifiedTransactionCommentNumbers = null;
        if($maxItemNumber >= 1){
            $maxNotifiedTransactionCommentNumbers = array_fill(0,$maxItemNumber,null);
        }//$maxItemNumber&1

        $notifiedItemNumber = 0;

        for($itemNumber = 1; $itemNumber<=$maxItemNumber; $itemNumber++){
            $item = $items->get($itemNumber - 1);
            $itemId = $item->id;
            $transactionComments = self::getNotifiedTransactionCommentsFromItemIdAndUserId(
                $itemId,
                $userId
            );
            $maxTransactionCommentNumber = 0;
            if($transactionComments && !$transactionComments->isEmpty()){
                $maxTransactionCommentNumber = $transactionComments->count();
            }//$transactionComments
            if($maxTransactionCommentNumber >= 1){
                $notifiedItemNumber = $notifiedItemNumber + 1;
            }//$maxTransactionCommentNumber&1

            $maxNotifiedTransactionCommentNumbers[$itemNumber - 1] = $maxTransactionCommentNumber;

        }//$itemNumber

        $maxNotifiedItemNumber = $notifiedItemNumber;

        $notifiedProperties =[
            "maxNotifiedItemNumber" => $maxNotifiedItemNumber,
            "maxNotifiedTransactionCommentNumbers" => $maxNotifiedTransactionCommentNumbers,
        ];

        return($notifiedProperties);

    }//
}