<?php

namespace App\Constants;

final class TransactionCommentStatus{
    public const UNDEFINED = 'undefined';
    public const DRAFT = 'draft';
    public const PUBLISHED = 'published';

    public static function toArray(){
        return([
            "UNDEFINED" => self::UNDEFINED,
            "DRAFT" => self::DRAFT,
            "PUBLISHED" => self::PUBLISHED,
        ]);
    }
}//Message