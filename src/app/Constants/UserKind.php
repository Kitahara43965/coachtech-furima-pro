<?php

namespace App\Constants;

final class UserKind{
    public const UNDEFINED = null;
    public const POSTED_USER = "posted-user";
    public const COUNTERPART_USER = "counterpart-user";

    public static function toArray(){
        return([
            "UNDEFINED" => self::UNDEFINED,
            "POSTED_USER" => self::POSTED_USER,
            "COUNTERPART_USER" => self::COUNTERPART_USER,
        ]);
    }
}//Message