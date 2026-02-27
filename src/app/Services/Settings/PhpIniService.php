<?php

namespace App\Services\Settings;

use App\Constants\Files\PhpIniArgumentName;

class PhpIniService
{
    public static function getUploadMaxFilesize()
    {
        return ini_get(PhpIniArgumentName::UPLOAD_MAX_FILESIZE);
    }

    public static function getPostMaxSize()
    {
        return ini_get(PhpIniArgumentName::POST_MAX_SIZE);
    }

    public static function getMemoryLimit()
    {
        return ini_get(PhpIniArgumentName::MEMORY_LIMIT);
    }

    private static function parseSize($stringSizeWrittenInPhpIniFile)
    {
        // サイズが空の場合、0を返す
        if (empty($stringSizeWrittenInPhpIniFile)) {
            return 0;
        }

        // 単位を小文字に変換
        $unit = strtolower(substr($stringSizeWrittenInPhpIniFile, -1)); // 単位 (T, M, K, G などを小文字に変換)
        $value = (int)substr($stringSizeWrittenInPhpIniFile, 0, -1); // 数値部分

        // 数値が0ならそのまま返す
        if ($value == 0) {
            return 0;
        }

        switch ($unit) {
            case 't': 
                return $value * 1024 * 1024 * 1024 * 1024; // テラバイト (T, t)
            case 'g': 
                return $value * 1024 * 1024 * 1024; // ギガバイト (G, g)
            case 'm': 
                return $value * 1024 * 1024; // メガバイト (M, m)
            case 'k': 
                return $value * 1024;        // キロバイト (K, k)
            default: 
                // 無効な単位（または単位なし）を検出して例外を投げる
                return $value; // バイト単位の場合、そのまま返す（単位なし）
        }
    }

    public static function getUploadMaxFilesizeInBytes()
    {
        return self::parseSize(self::getUploadMaxFilesize());
    }

    public static function getPostMaxSizeInBytes()
    {
        return self::parseSize(self::getPostMaxSize());
    }

    public static function getMemoryLimitInBytes()
    {
        return self::parseSize(self::getMemoryLimit());
    }

    public static function getPhpIniSettingSizesInBytes(){
        $phpIniSettingSizesInBytes = [
            PhpIniArgumentName::UPLOAD_MAX_FILESIZE => self::getUploadMaxFilesizeInBytes(),
            PhpIniArgumentName::POST_MAX_SIZE => self::getPostMaxSizeInBytes(),
            PhpIniArgumentName::MEMORY_LIMIT => self::getMemoryLimitInBytes(),
        ];

        return $phpIniSettingSizesInBytes;
    }

}