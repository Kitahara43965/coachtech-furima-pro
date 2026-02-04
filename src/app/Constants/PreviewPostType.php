<?php

namespace App\Constants;

final class PreviewPostType{
    public const UNDEFINED = null;
    public const DRAFT = "draft";
    public const STORE = "store";
    public const IMAGE_UPLOAD = "image-upload";

    public static function toArray(){
        return([
            "UNDEFINED" => self::UNDEFINED,
            "DRAFT" => self::DRAFT,
            "STORE" => self::STORE,
            "IMAGE_UPLOAD" => self::IMAGE_UPLOAD,
        ]);
    }
}//Message