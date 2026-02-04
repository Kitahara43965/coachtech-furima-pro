<?php

namespace App\Services;

use App\Constants\PreviewErrorStatus;
use App\Constants\PreviewPostType;

class PreviewErrorService{
    public static function attachPreviewErrorStatus(
        $validator,
        $previewPostType,
        $commentTextareaValue,
    ){
        $previewErrorStatus = PreviewErrorStatus::UNDEFINED;

        $hasValueCheck = false;
        $hasMaxCommentTextareaValueCharNumberCheck = false;
        $hasImageExtensionCheck = false;
        if($previewPostType === PreviewPostType::DRAFT){
            $hasValueCheck = false;
            $hasMaxCommentTextareaValueCharNumberCheck = true;
            $hasImageExtensionCheck = false;
        }else if($previewPostType === PreviewPostType::STORE){
            $hasValueCheck = true;
            $hasMaxCommentTextareaValueCharNumberCheck = true;
            $hasImageExtensionCheck = false;
        }else if($previewPostType === PreviewPostType::IMAGE_UPLOAD){
            $hasValueCheck = false;
            $hasMaxCommentTextareaValueCharNumberCheck = false;
            $hasImageExtensionCheck = true;
        }//$previewPostType

        $maxCommentTextareaValueCharNumber = 0;
        if($commentTextareaValue){
            $maxCommentTextareaValueCharNumber = mb_strlen($commentTextareaValue);
        }//$commentTextareaValue

        if($commentTextareaValue === null || $commentTextareaValue === ''){
            $hasValue = false;
        }else{
            $hasValue = true;
        }

        if($hasValueCheck === true){
            if($hasValue === false){
                if($previewErrorStatus === PreviewErrorStatus::UNDEFINED){
                    $previewErrorStatus = PreviewErrorStatus::NO_COMMENT_TEXTAREA_VALUE;
                }//$previewErrorStatus
            }//$hasValue
        }//$hasValueCheck&true

        if($hasMaxCommentTextareaValueCharNumberCheck === true){
            if($maxCommentTextareaValueCharNumber > PreviewErrorStatus::MAX_COMMENT_TEXTAREA_VALUE_CHAR_NUMBER){
                if($previewErrorStatus === PreviewErrorStatus::UNDEFINED){
                    $previewErrorStatus = PreviewErrorStatus::TOO_LONG_COMMENT_TEXTAREA_VALUE;
                }//$previewErrorStatus
            }//$maxCommentTextareaValueCharNumber
        }//$hasMaxCommentTextareaValueCharNumberCheck



        $previewErrorMessage = PreviewErrorStatus::message($previewErrorStatus);

        if($previewErrorStatus !== PreviewErrorStatus::UNDEFINED){
            if(PreviewErrorStatus::PREVIEW_VALIDATION_MESSAGE_NAME){
                $validator->errors()->add(PreviewErrorStatus::PREVIEW_VALIDATION_MESSAGE_NAME,$previewErrorMessage);
            }//$stringFieldName
        }//$timeFieldParamsCheckinAt

        $results = [
            "previewErrorStatus" => $previewErrorStatus,
        ];

        return($results);

    }

}