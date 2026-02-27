<?php

namespace App\Services;

class AttributeService
{
    public static function getAttributeFromAttributePrefixAndDBId($attributePrefix, $dBId)
    {
        $attribute = null;
        if ($dBId) {
            $attribute = $attributePrefix . $dBId;
        } //dBId
        return $attribute;
    }
}