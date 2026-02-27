<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Constants\Files\FileName;
use App\Models\User;

class FileService{

    public static function getQuestionMarkPath(){
        $noTransactionImagesDirectory = FileName::NO_TRANSACTION_IMAGES_DIRECTORY;
        $questionMark = Filename::QUESTION_MARK;
        $questionMarkPath = $noTransactionImagesDirectory.DIRECTORY_SEPARATOR.$questionMark;
        return $questionMarkPath;
    }

    public static function getDefaultProfileImageNamePath(){
        $defaultProfileImageDirectory = FileName::DEFAULT_PROFILE_IMAGE_DIRECTORY;
        $defaultProfileImageName = FileName::DEFAULT_PROFILE_IMAGE_NAME;
        $defaultProfileImageNamePath = $defaultProfileImageDirectory.DIRECTORY_SEPARATOR.$defaultProfileImageName;
        return($defaultProfileImageNamePath);
    }


    public static function getUserImagePathFromUserId($userId){
        $user = $userId ? User::find($userId) : null;
        $userImage = $user ? $user->image : null;
        $userImagePath = null;
        if($userImage){
            $userImagePath = FileName::USER_IMAGE_DIRECTORY.DIRECTORY_SEPARATOR.$userImage;
        }//$userImage
        return($userImagePath);
    }

    public static function getUserItemDirectoryPathFromUserItemId($userItemId){
        if($userItemId){
            $userItemDirectoryPath = FileName::USER_ITEM_DIRECTORY."_".$userItemId;
        }else{//$userItemId
            $userItemDirectoryPath = FileName::USER_ITEM_DIRECTORY;
        }//$userItemId
        return($userItemDirectoryPath);
    }

    public static function getUserItemTransactionImageDirectoryPathFromUserItemId($userItemId){
        $userItemDirectoryPath = FileService::getUserItemDirectoryPathFromUserItemId($userItemId);
        $userItemTransactionImageDirectoryPath
             = $userItemDirectoryPath.DIRECTORY_SEPARATOR.FileName::USER_ITEM_TRANSACTION_IMAGE_DIRECTORY;
        return($userItemTransactionImageDirectoryPath);
    }
    
}