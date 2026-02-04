<?php

namespace App\Constants\ShowFunctionKinds;

final class ShowFunctionKind{
    public const UNDEFINED = 0;
    public const LOGIN = 1;
    public const REGISTER = 2;
    public const SHOW_EMAIL_VERIFICATION = 3;
    public const INDEX_INDEX = 4;
    public const MY_LIST_INDEX = 5;
    public const SOLD_GOODS_MYPAGE = 6;
    public const BOUGHT_GOODS_MYPAGE = 7;
    public const DEAL_GOODS_MYPAGE = 8;
    public const SELL = 9;
    public const ITEM_EDIT_ITEM_ID = 10;
    public const ITEM_ITEM_ID = 11;
    public const PURCHASE_ITEM_ID = 12;
    public const PURCHASE_ADDRESS_ITEM_ID = 13;
    public const MYPAGE_PROFILE = 14;
    public const ITEM_DEAL_ITEM_ID = 15;

     public static function toArray()
    {
        return [
            'UNDEFINED' => self::UNDEFINED,
            'LOGIN' => self::LOGIN,
            'REGISTER' => self::REGISTER,
            'SHOW_EMAIL_VERIFICATION' => self::SHOW_EMAIL_VERIFICATION,
            'INDEX_INDEX' => self::INDEX_INDEX,
            'MY_LIST_INDEX' => self::MY_LIST_INDEX,
            'SOLD_GOODS_MYPAGE' => self::SOLD_GOODS_MYPAGE,
            'BOUGHT_GOODS_MYPAGE' => self::BOUGHT_GOODS_MYPAGE,
            'DEAL_GOODS_MYPAGE' => self::DEAL_GOODS_MYPAGE,
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
