<?php

namespace App\Services;

use App\Constants\ShownItemsKind;
use App\Models\User;
use App\Models\Item;
use App\Models\Rating;
use App\DTOs\RatingDTO;
use App\Constants\RatingType;

class RatingService{
    public static function getRatingDTOFromUserIdAndItemId($fromUserId,$itemId){

        $item = Item::find($itemId);
        $itemBuyer = null;
        $itemSeller = null;
        if($item){
            $itemBuyer = $item->purchasedByUsers()->first();
            $itemSeller = $item->usersByOwnership()->first();
        }//$item

        $itemBuyerId = $itemBuyer ? $itemBuyer->id : null;
        $itemSellerId = $itemSeller ? $itemSeller->id : null;

        $type = null;
        $toUserId = null;
        if($fromUserId && $item){
            if($fromUserId === $itemBuyerId){
                $type = RatingType::BUYER_TO_SELLER;
                $toUserId = $itemSellerId;
            }else if($fromUserId === $itemSellerId){
                $type = RatingType::SELLER_TO_BUYER;
                $toUserId = $itemBuyerId;
            }//$fromUserId
        }//$item

        $existingRating = null;
        if($type && $toUserId){
            $existingRating = Rating::where('from_user_id', $fromUserId)
                        ->where('to_user_id', $toUserId)
                        ->where('item_id', $itemId)
                        ->where('type', $type)
                        ->first();
        }//$type

        $isRatingExistence = false;
        $existingRatingId = null;
        if($existingRating){
            $isRatingExistence = true;
            $existingRatingId = $existingRating->id;
        }//existingRating

        $existingRatingDTO = new RatingDTO();
        $existingRatingDTO->from_user_id = $fromUserId;
        $existingRatingDTO->to_user_id = $toUserId;
        $existingRatingDTO->item_id = $itemId;
        $existingRatingDTO->type = $type;
        $existingRatingDTO->is_rating_existence = $isRatingExistence;
        $existingRatingDTO->rating_id = $existingRatingId;

        return($existingRatingDTO);
    }
}//RatingService