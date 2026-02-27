<?php

namespace App\Services;

use App\Models\User;
use App\DTOs\UserDTO;
use App\Services\FileService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UserDTOService{
    public function getUserDTOFromUserId($userId){
        $user = $userId ? User::find($userId) : null;
        $userDTO = null;
        if($user){
            $userDTO = new UserDTO();
            $userDTO->user_id = $user->id;
            $userDTO->login_time_number = $user->login_time_number;
            $userDTO->name = $user->name;
            $userDTO->email = $user->email;
            $userDTO->email_verified_at = $user->email_verified_at;
            $userDTO->password = $user->password;
            $userDTO->rememberToken = $user->rememberToken;
            $userDTO->is_filled_with_profile = $user->is_filled_with_profile;
            $userDTO->username = $user->username;
            $userDTO->postcode = $user->postcode;
            $userDTO->address = $user->address;
            $userDTO->building = $user->building;
            $userDTO->image = $user->image;
            $userDTO->created_at = $user->created_at;
            $userDTO->updated_at = $user->updated_at;
            $imagePath = FileService::getUserImagePathFromUserId($userId);

            Log::debug(asset('storage/'.$imagePath));

            $defaultProfileImageNamePath = FileService::getDefaultProfileImageNamePath();
            $imageUrl = null;
            if (Storage::disk('public')->exists($imagePath)) { 
                $imageUrl = asset('storage/'.$imagePath);
            } else {
                $imageUrl = asset('storage/'.$defaultProfileImageNamePath);
            }
            $userDTO->image_url = $imageUrl;
        }//$user

        return($userDTO);
    }

}//UserDTO