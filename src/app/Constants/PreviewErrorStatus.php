<?php

namespace App\Constants;

final class PreviewErrorStatus{
    public const UNDEFINED = 0;
    public const NO_COMMENT_TEXTAREA_VALUE = 1;
    public const NO_DB_COMMENT_TEXTAREA_VALUE = 2;
    public const TOO_LONG_COMMENT_TEXTAREA_VALUE = 3;
    public const INVALID_IMAGE_FILE = 4;

    public const MAX_COMMENT_TEXTAREA_VALUE_CHAR_NUMBER = 400;
    public const COMMENT_TEXTAREA_NAME = "comment-textarea-name";
    public const PREVIEW_VALIDATION_MESSAGE_NAME = "preview-validation-message-name";

    public static function message($errorStatus)
    {
        switch ($errorStatus) {
            case self::UNDEFINED:
                return '有効な入力です。';
            case self::NO_COMMENT_TEXTAREA_VALUE:
                return '本文を入力してください';
            case self::NO_DB_COMMENT_TEXTAREA_VALUE:
                return '本文の入力がありましたが、保存されていませんでした。再度本文を入力してください。';
            case self::TOO_LONG_COMMENT_TEXTAREA_VALUE:
                return '本文は'.self::MAX_COMMENT_TEXTAREA_VALUE_CHAR_NUMBER.'文字以内で入力してください。';
            case self::INVALID_IMAGE_FILE:
                return '「.png」または「.jpeg」形式でアップロードしてください';
            default:
                return '不明なステータスです。';
        }
    }

}//PreviewErrorStatus