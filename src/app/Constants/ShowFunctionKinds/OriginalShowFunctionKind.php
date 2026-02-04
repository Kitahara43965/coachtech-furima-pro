<?php

namespace App\Constants\ShowFunctionKinds;

final class OriginalShowFunctionKind{
    public const UNDEFINED = 0;
    public const LOGIN = 1;
    public const REGISTER = 2;
    public const SHOW_EMAIL_VERIFICATION = 3;
    public const INDEX = 4;
    public const MYPAGE = 5;
    public const SELL = 6;
    public const ITEM_EDIT_ITEM_ID = 7;
    public const ITEM_ITEM_ID = 8;
    public const PURCHASE_ITEM_ID = 9;
    public const PURCHASE_ADDRESS_ITEM_ID = 10;
    public const MYPAGE_PROFILE = 11;
    public const ITEM_DEAL_ITEM_ID = 12;

     public static function toArray()
    {
        return [
            'UNDEFINED' => self::UNDEFINED,
            'LOGIN' => self::LOGIN,
            'REGISTER' => self::REGISTER,
            'SHOW_EMAIL_VERIFICATION' => self::SHOW_EMAIL_VERIFICATION,
            'INDEX' => self::INDEX,
            'MYPAGE' => self::MYPAGE,
            'SELL' => self::SELL,
            'ITEM_EDIT_ITEM_ID' => self::ITEM_EDIT_ITEM_ID,
            'ITEM_ITEM_ID' => self::ITEM_ITEM_ID,
            'PURCHASE_ITEM_ID' => self::PURCHASE_ITEM_ID,
            'PURCHASE_ADDRESS_ITEM_ID' => self::PURCHASE_ADDRESS_ITEM_ID,
            'MYPAGE_PROFILE' => self::MYPAGE_PROFILE,
            'ITEM_DEAL_ITEM_ID' => self::ITEM_DEAL_ITEM_ID,
        ];
    }


}
