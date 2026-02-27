<?php

namespace App\Constants;

final class PreviewPostType{
    public const UNDEFINED = null;
    public const INITIAL_PREVIEW = "initial-preview";
    public const DRAFT = "draft";
    public const STORE = "store";
    public const COMMENT_EDIT = "comment-edit";
    public const COMMENT_DELETE = "comment-delete";
    public const NEW_IMAGE_UPLOAD = "new-image-upload";
    public const NEW_IMAGE_DELETE = "new-image-delete";

    public static function toArray(){
        return([
            "UNDEFINED" => self::UNDEFINED,
            "INITIAL_PREVIEW" => self::INITIAL_PREVIEW,
            "DRAFT" => self::DRAFT,
            "STORE" => self::STORE,
            "COMMENT_EDIT" => self::COMMENT_EDIT,
            "COMMENT_DELETE" => self::COMMENT_DELETE,
            "NEW_IMAGE_UPLOAD" => self::NEW_IMAGE_UPLOAD,
            "NEW_IMAGE_DELETE" => self::NEW_IMAGE_DELETE,
        ]);
    }
}//Message