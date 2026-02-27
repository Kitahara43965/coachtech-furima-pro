<?php

namespace App\Services;

use App\Models\User;
use App\Models\Item;
use App\Models\UserItem;
use App\Models\TransactionComment;
use App\Models\TransactionImage;
use App\DTOs\TransactionCommentDTO;
use App\DTOs\TransactionImageDTO;
use App\Constants\TransactionCommentStatus;
use App\Services\TransactionCommentService;
use App\Services\FileService;
use App\Constants\Files\FileName;
use Illuminate\Support\Facades\Storage;

class TransactionCommentDTOService{

    public function getTransactionCommentDTOFromTransactionCommentAndUserItemId(
        $transactionComment,
        $userItemId
    ){
        $userItemTransactionImageDirectoryPath
             = FileService::getUserItemTransactionImageDirectoryPathFromUserItemId($userItemId);

        $transactionCommentDTO = new TransactionCommentDTO();
        if($transactionComment instanceOf TransactionComment){
            $transactionCommentDTO->transaction_comment_id = $transactionComment->id;
            $transactionCommentDTO->user_item_id = $transactionComment->user_item_id;
            $currentUserId = $transactionComment->user_id;
            $transactionCommentDTO->user_id = $currentUserId;
            $transactionCommentDTO->comment = $transactionComment->comment;
            $transactionCommentDTO->status = $transactionComment->status;
            $transactionCommentDTO->is_watched = $transactionComment->is_watched;
            $transactionCommentDTO->created_at = $transactionComment->created_at;
            $transactionCommentDTO->updated_at = $transactionComment->updated_at;

            $currentUser = User::find($currentUserId);
            $currentUserName = $currentUser ? $currentUser->name : null;
            $currentUserUserName = $currentUser ? $currentUser->username : null;
            $transactionCommentDTO->user_name = $currentUserName;
            $transactionCommentDTO->user_username = $currentUserUserName;

            $transactionImages = $transactionComment
                ->transactionImages()
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $maxTransactionImageNumber = 0;
            if($transactionImages){
                $maxTransactionImageNumber = $transactionImages->count();
            }//$transactionImages
            $transactionImageDTOs = null;
            if($maxTransactionImageNumber >= 1){
                $transactionImageDTOs = array_fill(0,$maxTransactionImageNumber,null);
            }//$maxTransactionImageNumber&1

            for(
                $transactionImageNumber = 1;
                $transactionImageNumber <= $maxTransactionImageNumber;
                $transactionImageNumber ++
            ){
                $transactionImage = $transactionImages[$transactionImageNumber - 1];
                $transactionImageDTO = new TransactionImageDTO();
                $transactionImageDTO->transaction_image_id = $transactionImage->id;
                $transactionImageDTO->transaction_comment_id = $transactionImage->transaction_comment_id;
                $transactionImageDTO->image = $transactionImage->image;
                $transactionImageDTO->created_at = $transactionImage->created_at;
                $transactionImageDTO->updated_at = $transactionImage->updated_at;
                $transactionImagePath = $userItemTransactionImageDirectoryPath.DIRECTORY_SEPARATOR.$transactionImage->image;

                $questionMarkPath = FileService::getQuestionMarkPath();

                if (Storage::disk('public')->exists($transactionImagePath)) { 
                    $imageUrl = asset('storage/'.$transactionImagePath); 
                } else {
                    $imageUrl = asset('storage/'.$questionMarkPath);
                }

                $transactionImageDTO->image_url = $imageUrl;
                $transactionImageDTOs[$transactionImageNumber - 1] = $transactionImageDTO;
            }//$transactionImageNumber

            $transactionCommentDTO->transaction_image_dtos = $transactionImageDTOs;
        }//transactionComment instanceOf TransactionComment
        return($transactionCommentDTO);
    }//getTransactionCommentDTOFromTransactionComment
    
    public function getCurrentTransactionCommentDTOsFromUserItemIdAndUserId(
        $userItemId,
        $userId
    ){

        $currentTransactionComments
             = TransactionCommentService::getCurrentTransactionCommentsFromUserItemIdAndUserId($userItemId,$userId);

        $maxCurrentTransactionCommentNumber = 0;
        if($currentTransactionComments){
            $maxCurrentTransactionCommentNumber = $currentTransactionComments->count();
        }//}//$currentTransactionComments
        $currentTransactionCommentDTOs = null;
        if($maxCurrentTransactionCommentNumber >= 1){
            $currentTransactionCommentDTOs = array_fill(0,$maxCurrentTransactionCommentNumber,null);
        }//$maxCurrentTransactionCommentNumber&1

        for(
            $currentTransactionCommentNumber = 1;
            $currentTransactionCommentNumber <= $maxCurrentTransactionCommentNumber;
            $currentTransactionCommentNumber++
        ){
            $currentTransactionComment = $currentTransactionComments[$currentTransactionCommentNumber - 1];
            $currentTransactionCommentDTO = $this->getTransactionCommentDTOFromTransactionCommentAndUserItemId(
                $currentTransactionComment,
                $userItemId,
            );
            $currentTransactionCommentDTOs[$currentTransactionCommentNumber - 1] = $currentTransactionCommentDTO;
        }//$currentTransactionCommentNumber

        return($currentTransactionCommentDTOs);

    }//getOrderedTransactionImages

    public function getCurrentTransactionCommentDTOsFromItemIdAndUserId($itemId,$userId = null){

        $userItemId = TransactionCommentService::getPurchasedUserItemIdFromItemIdAndUserId(
            $itemId,
            $userId
        );

        $transactionCommentDTOService = new TransactionCommentDTOService();
        $currentTransactionCommentDTOs
             = $transactionCommentDTOService
                ->getCurrentTransactionCommentDTOsFromUserItemIdAndUserId($userItemId,$userId);

        return($currentTransactionCommentDTOs);

    }//getCurrentTransactionCommentDTOsFromItemIdAndUserId
}