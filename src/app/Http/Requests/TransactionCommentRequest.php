<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use App\Services\PreviewErrorService;
use App\Constants\PreviewErrorStatus;
use App\Constants\PreviewPostType;
use App\Constants\NamePrefix;
use App\Services\TransactionCommentService;
use App\Services\TransactionCommentDTOService;
use App\Services\AttributeService;
use App\Models\TransactionComment;


class TransactionCommentRequest extends FormRequest
{

    public function selectedItemId(){
        return $this->selectedItemId;
    }

    public function postedUserId(){
        return $this->postedUserId;
    }

    public function previewPostType(){
        return $this->previewPostType;
    }

    public function targetTransactionCommentId(){
        return $this->targetTransactionCommentId;
    }

    public function newCommentTextareaInputTargetTransactionCommentComment(){
        return $this->newCommentTextareaInputTargetTransactionCommentComment;
    }

    public function deletedTransactionCommentIds(){
        return $this->deletedTransactionCommentIds;
    }

    public function deletedTransactionImageIds(){
        return $this->deletedTransactionImageIds;
    }

    public function addedTransactionImageFiles(){
        return $this->addedTransactionImageFiles;
    }


    public function jsCodeNumber()
    {
        return $this->jsCodeNumber;
    }

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
        ];
    }

    public function wantsJson()
    {
        return true;
    }

    public static function attachPreviewErrorStatus(
        $validator,
        $selectedItemId,
        $postedUserId,
        $previewPostType,
        $targetTransactionCommentId,
        $newCommentTextareaInputTargetTransactionCommentComment,
        $deletedTransactionCommentIds,
        $deletedTransactionImageIds,
        $addedTransactionImageFiles,
        $commentTextareaTransactionCommentIds,
        $commentTextareaTransactionCommentComments,
        $currentTransactionCommentDTOs,
    ){
        $transactionCommentErrorMessageNamePrefix = NamePrefix::TRANSACTION_COMMENT_ERROR_MESSAGE;
        $transactionCommentCommentTextareaNamePrefix = NamePrefix::TRANSACTION_COMMENT_COMMENT_TEXTAREA;

        $maxCommentTextareaNumber = 0;
        if($commentTextareaTransactionCommentIds){
            $maxCommentTextareaNumber = count($commentTextareaTransactionCommentIds);
        }//$commentTextareaTransactionCommentIds

        for(
            $commentTextareaNumber = 1;
            $commentTextareaNumber <= $maxCommentTextareaNumber;
            $commentTextareaNumber++
        ){
            $previewErrorStatus = PreviewErrorStatus::UNDEFINED;
            $commentTextareaTransactionCommentId = $commentTextareaTransactionCommentIds[$commentTextareaNumber - 1];
            $commentTextareaTransactionCommentComment
                = $commentTextareaTransactionCommentComments[$commentTextareaNumber - 1];
            $originalCommentTextareaTransactionComment = TransactionComment::find($commentTextareaTransactionCommentId);
            $originalCommentTextareaTransactionCommentComment = null;
            if($originalCommentTextareaTransactionComment){
                $originalCommentTextareaTransactionCommentComment = $originalCommentTextareaTransactionComment->comment;
            }//$originalCommentTextareaTransactionComment
                
            $hasValueCheck = false;
            $hasDBValueCheck = false;
            $hasMaxCommentTextareaValueCharNumberCheck = false;
            $hasImageExtensionCheck = false;

            if($previewPostType === PreviewPostType::INITIAL_PREVIEW){
                $hasValueCheck = false;
                $hasDBValueCheck = false;
                $hasMaxCommentTextareaValueCharNumberCheck = false;
                $hasImageExtensionCheck = false;
            }else if($previewPostType === PreviewPostType::DRAFT){
                $hasValueCheck = false;
                $hasDBValueCheck = false;
                $hasMaxCommentTextareaValueCharNumberCheck = true;
                $hasImageExtensionCheck = false;
            }else if($previewPostType === PreviewPostType::STORE){
                if($commentTextareaTransactionCommentId === $targetTransactionCommentId){
                    $hasValueCheck = true;
                }else{//$commentTextareaTransactionCommentId
                    $hasValueCheck = false;
                }//$commentTextareaTransactionCommentId
                $hasDBValueCheck = true;
                $hasMaxCommentTextareaValueCharNumberCheck = true;
                $hasImageExtensionCheck = false;
            }else if($previewPostType === PreviewPostType::COMMENT_EDIT){
                $hasValueCheck = false;
                $hasDBValueCheck = false;
                $hasMaxCommentTextareaValueCharNumberCheck = false;
                $hasImageExtensionCheck = true;
            }else if($previewPostType === PreviewPostType::COMMENT_DELETE){
                $hasValueCheck = false;
                $hasDBValueCheck = false;
                $hasMaxCommentTextareaValueCharNumberCheck = false;
                $hasImageExtensionCheck = false;
            }else if($previewPostType === PreviewPostType::NEW_IMAGE_UPLOAD){
                $hasValueCheck = false;
                $hasDBValueCheck = false;
                $hasMaxCommentTextareaValueCharNumberCheck = false;
                if($commentTextareaTransactionCommentId === $targetTransactionCommentId){
                    $hasImageExtensionCheck = true;
                }else{//$commentTextareaTransactionCommentId
                    $hasImageExtensionCheck = false;
                }//$commentTextareaTransactionCommentId
            }else if($previewPostType === PreviewPostType::NEW_IMAGE_DELETE){
                $hasValueCheck = false;
                $hasDBValueCheck = false;
                $hasMaxCommentTextareaValueCharNumberCheck = false;
                $hasImageExtensionCheck = false;
            }//$previewPostType

            $transactionCommentErrorMessageName = AttributeService::getAttributeFromAttributePrefixAndDBId(
                $transactionCommentErrorMessageNamePrefix,
                $commentTextareaTransactionCommentId
            );

            $transactionCommentCommentTextareaName = AttributeService::getAttributeFromAttributePrefixAndDBId(
                $transactionCommentCommentTextareaNamePrefix,
                $commentTextareaTransactionCommentId
            );

            $maxCommentTextareaValueCharNumber = 0;
            if($commentTextareaTransactionCommentComment){
                $maxCommentTextareaValueCharNumber = mb_strlen($commentTextareaTransactionCommentComment);
            }//$commentTextareaTransactionCommentComment

            if($hasMaxCommentTextareaValueCharNumberCheck === true){
                if($maxCommentTextareaValueCharNumber > PreviewErrorStatus::MAX_COMMENT_TEXTAREA_VALUE_CHAR_NUMBER){
                    if($previewErrorStatus === PreviewErrorStatus::UNDEFINED){
                        $previewErrorStatus = PreviewErrorStatus::TOO_LONG_COMMENT_TEXTAREA_VALUE;
                    }//$previewErrorStatus
                }//$maxCommentTextareaValueCharNumber
            }//$hasMaxCommentTextareaValueCharNumberCheck
            
            if($commentTextareaTransactionCommentComment === null || $commentTextareaTransactionCommentComment === ''){
                if($hasValueCheck === true){
                    if($previewErrorStatus === PreviewErrorStatus::UNDEFINED){
                        $previewErrorStatus = PreviewErrorStatus::NO_COMMENT_TEXTAREA_VALUE;
                    }//$previewErrorStatus
                }//$hasValueCheck&true
            }else{//$commentTextareaTransactionCommentComment
                if($originalCommentTextareaTransactionCommentComment === null || $originalCommentTextareaTransactionCommentComment === ''){
                    if($hasDBValueCheck === true){
                        if($previewErrorStatus === PreviewErrorStatus::UNDEFINED){
                            $previewErrorStatus = PreviewErrorStatus::NO_DB_COMMENT_TEXTAREA_VALUE;
                        }//$previewErrorStatus
                    }//$hasDBValueCheck&true
                }//$originalCommentTextareaTransactionCommentComment
            }//$commentTextareaTransactionCommentComment

            $maxAddedTransactionImageFileNumber = 0;
            if($addedTransactionImageFiles){
               $maxAddedTransactionImageFileNumber = count($addedTransactionImageFiles);
            }//$addedTransactionImageFiles

            if($hasImageExtensionCheck === true){
                for(
                    $addedTransactionImageFileNumber = 1;
                    $addedTransactionImageFileNumber <= $maxAddedTransactionImageFileNumber;
                    $addedTransactionImageFileNumber ++
                ){
                    $addedTransactionImageFile = $addedTransactionImageFiles[$addedTransactionImageFileNumber - 1];
                    $addedTransactionImageFileExtension = $addedTransactionImageFile->getClientOriginalExtension();
                    if($addedTransactionImageFileExtension === 'png'){
                    }else if($addedTransactionImageFileExtension === 'jpeg'){
                    }else{//$addedTransactionImageFileExtension
                        if($previewErrorStatus === PreviewErrorStatus::UNDEFINED){
                            $previewErrorStatus = PreviewErrorStatus::INVALID_IMAGE_FILE;
                        }//$previewErrorStatus
                    }//$addedTransactionImageFileExtension

                }
            }//$hasImageExtensionCheck

            $previewErrorMessage = PreviewErrorStatus::message($previewErrorStatus);

            if($previewErrorStatus !== PreviewErrorStatus::UNDEFINED){
                if($transactionCommentErrorMessageName){
                    $validator->errors()->add($transactionCommentErrorMessageName,$previewErrorMessage);
                }//$stringFieldName
            }//$timeFieldParamsCheckinAt

        }//$commentTextareaNumber

    }

    public function failedValidation(Validator $validator)
    {
        $jsCodeNumber = 422;
        $selectedItemId = (int)$this->input('selectedItemId');
        $postedUserId = (int)$this->input('postedUserId');
        $previewPostType = $this->input('previewPostType');
        $targetTransactionCommentId = $this->input('targetTransactionCommentId');
        $newCommentTextareaInputTargetTransactionCommentComment
             = $this->input('newCommentTextareaInputTargetTransactionCommentComment');
        $deletedTransactionCommentIds
            = $this->input('deletedTransactionCommentIds');
        $deletedTransactionImageIds
             = $this->input('deletedTransactionImageIds');
        $addedTransactionImageFiles = $this->file('addedTransactionImageFiles');
        $commentTextareaTransactionCommentIds
                = $this->input('commentTextareaTransactionCommentIds');
        $commentTextareaTransactionCommentComments
                = $this->input('commentTextareaTransactionCommentComments');


        $transactionCommentDTOService = new TransactionCommentDTOService();
        $purchasedPostedUserItemId = TransactionCommentService::getPurchasedUserItemIdFromItemIdAndUserId(
            $selectedItemId,
            $postedUserId
        );

        $currentTransactionCommentDTOs = $transactionCommentDTOService->getCurrentTransactionCommentDTOsFromUserItemIdAndUserId(
            $purchasedPostedUserItemId,
            $postedUserId
        );

        $newDraftTransactionComment = TransactionCommentService::getDraftTransactionCommentFromUserItemIdAndUserId(
            $purchasedPostedUserItemId,
            $postedUserId
        );

        $errorMessages = $validator->errors()->messages();
        $results = [
            "jsCodeNumber" => $jsCodeNumber,
            'selectedItemId' => $selectedItemId,
            'postedUserId' => $postedUserId,
            'previewPostType' => $previewPostType,
            'currentTransactionCommentDTOs' => $currentTransactionCommentDTOs,
            'errorMessages' => $errorMessages,
        ];
        throw new ValidationException($validator, response()->json($results, $jsCodeNumber));
    }

    public function withValidator($validator): void
    {
        $jsCodeNumber = 200;

        $validator->after(function ($validator) use ($jsCodeNumber) {
            $selectedItemId = (int)$this->input('selectedItemId');
            $postedUserId = (int)$this->input('postedUserId');
            $previewPostType = $this->input('previewPostType');
            $input = $this->all();
            $targetTransactionCommentId
                = $this->input('targetTransactionCommentId');
            $newCommentTextareaInputTargetTransactionCommentComment
                = $this->input('newCommentTextareaInputTargetTransactionCommentComment');
            $deletedTransactionCommentIds
                = $this->input('deletedTransactionCommentIds');
            $deletedTransactionImageIds
                = $this->input('deletedTransactionImageIds');
            $addedTransactionImageFiles = $this->file('addedTransactionImageFiles');
            $commentTextareaTransactionCommentIds
                = $this->input('commentTextareaTransactionCommentIds');
            $commentTextareaTransactionCommentComments
                = $this->input('commentTextareaTransactionCommentComments');

            $transactionCommentDTOService = new TransactionCommentDTOService();
            $purchasedPostedUserItemId = TransactionCommentService::getPurchasedUserItemIdFromItemIdAndUserId(
                $selectedItemId,
                $postedUserId
            );

            $currentTransactionCommentDTOs = $transactionCommentDTOService->getCurrentTransactionCommentDTOsFromUserItemIdAndUserId(
                $purchasedPostedUserItemId,
                $postedUserId
            );

            $newDraftTransactionComment = TransactionCommentService::getDraftTransactionCommentFromUserItemIdAndUserId(
                $purchasedPostedUserItemId,
                $postedUserId
            );

            self::attachPreviewErrorStatus(
                $validator,
                $selectedItemId,
                $postedUserId,
                $previewPostType,
                $targetTransactionCommentId,
                $newCommentTextareaInputTargetTransactionCommentComment,
                $deletedTransactionCommentIds,
                $deletedTransactionImageIds,
                $addedTransactionImageFiles,
                $commentTextareaTransactionCommentIds,
                $commentTextareaTransactionCommentComments,
                $currentTransactionCommentDTOs,
            );

            $this->selectedItemId = $selectedItemId;
            $this->postedUserId = $postedUserId;
            $this->previewPostType = $previewPostType;
            $this->targetTransactionCommentId = $targetTransactionCommentId;
            $this->newCommentTextareaInputTargetTransactionCommentComment = $newCommentTextareaInputTargetTransactionCommentComment;
            $this->deletedTransactionCommentIds = $deletedTransactionCommentIds;
            $this->deletedTransactionImageIds = $deletedTransactionImageIds;
            $this->addedTransactionImageFiles = $addedTransactionImageFiles;
            $this->jsCodeNumber = $jsCodeNumber;
        });
    }

}
