<?php

namespace App\Constants\Files;

final class PhpIniArgumentName{
    public const UNDEFINED = 'undefined';
    public const UPLOAD_MAX_FILESIZE = 'upload_max_filesize';
    public const POST_MAX_SIZE = 'post_max_size';
    public const MEMORY_LIMIT = 'memory_limit';

    public static function toArray(){
        return([
            "UNDEFINED" => self::UNDEFINED,
            "UPLOAD_MAX_FILESIZE" => self::UPLOAD_MAX_FILESIZE,
            "POST_MAX_SIZE" => self::POST_MAX_SIZE,
            "MEMORY_LIMIT" => self::MEMORY_LIMIT,
        ]);
    }
}//PhpIniArgumentName