<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageFileNumberCountService;
use App\Constants\Files\FileName;

class ProfileController
{
    
    public function countImages()
    {
        $count = ImageFileNumberCountService::getPublicImageNumber(FileName::USER_IMAGE_DIRECTORY,FileName::USER_IMAGE_PREFIX);
        return response()->json(['count' => $count]);
    }

    public function mypageProfileUpdate(ProfileRequest $request)
    {
        $authenticatedUser = Auth::user();
        $previewUrl = null;
        $imageName = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalImageName = $file->getClientOriginalName();
            $extension = pathinfo($originalImageName, PATHINFO_EXTENSION);

            $count = ImageFileNumberCountService::getPublicImageNumber(FileName::USER_IMAGE_DIRECTORY,FileName::USER_IMAGE_PREFIX);
            $imageName = FileName::USER_IMAGE_PREFIX . ($count + 1) . '.' . $extension;

            // 保存
            $path = $file->storeAs(FileName::USER_IMAGE_DIRECTORY, $imageName, 'public');
            $previewUrl = asset('storage/'.$path);
        } else {
            $imageName = $request->input('image_name');
            $previewUrl = $request->input('preview_url');
        }

        $authenticatedUser->update([
            'is_filled_with_profile' => true,
            'username' => $request->input('username'),
            'postcode' => $request->input('postcode'),
            'address'  => $request->input('address'),
            'building' => $request->input('building'),
            'image'    => $imageName,
        ]);

        return redirect(route('index'))->with('imageMessage', '登録が完了しました！');
    }
}