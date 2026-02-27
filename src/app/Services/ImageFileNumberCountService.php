<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ImageFileNumberCountService
{
    public static function getPublicImageNumber($imageDirectoryName = null, $keyword = null)
    {
        $files = Storage::disk('public')->files($imageDirectoryName ?? '');

        if($keyword){
            if ($imageDirectoryName) {
                $regex = '/' . preg_quote($imageDirectoryName, '/') . '\/' . preg_quote($keyword, '/') . '/i';
            } else {
                $regex = '/' . preg_quote($keyword, '/') . '/i';
            }

            $imageFiles = array_filter($files, function ($file) use ($regex) {
                return preg_match($regex, $file);
            });
        }else{//$keyword
            $imageFiles = $files;
        }//$keyword

        return count($imageFiles);
    }
}//ImageFileNumberCountService