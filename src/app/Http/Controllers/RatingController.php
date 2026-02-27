<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
use App\Models\Rating;
use App\Http\Requests\RatingRequest;
use App\Constants\RatingType;
use App\Constants\UserItemStatus;
use App\Services\RatingService;
use Illuminate\Support\Facades\DB;
use App\Mail\TransactionCompletedMail;
use Illuminate\Support\Facades\Mail;

class RatingController extends Controller
{

    public function updateRatingFromRatingDTO($ratingDTO,$ratingValue){
        $fromUserId = null;
        $toUserId = null;
        $itemId = null;
        $type = null;
        $isRatingExistence = false;
        $ratingId = null;
        if($ratingDTO){
            $fromUserId = $ratingDTO->from_user_id;
            $toUserId = $ratingDTO->to_user_id;
            $itemId = $ratingDTO->item_id;
            $type = $ratingDTO->type;
            $isRatingExistence = $ratingDTO->is_rating_existence;
            $ratingId = $ratingDTO->rating_id;
        }//$ratingDTO

        $updatedExistingRatingData = [
            'from_user_id' => $fromUserId,
            'to_user_id'   => $toUserId,
            'item_id'      => $itemId,
            'type'         => $type,
            'rating_value' => $ratingValue,
        ];

        if($fromUserId&&$toUserId&&$type){
            if($isRatingExistence === true){
                $existingRating = Rating::find($ratingId);
                $existingRating->update($updatedExistingRatingData);
            }else{
                Rating::create($updatedExistingRatingData);
            }
        }//$type
    }

    public function completeAfterRatingFromItemIdAndUserId($itemId,$fromUserId)
    {
        $item = Item::find($itemId);
        $itemBuyer = $item ? $item->buyer() : null;
        $itemSeller = $item ? $item->seller() : null;
        $itemBuyerId = $itemBuyer ? $itemBuyer->id : null;
        $itemSellerId = $itemSeller ? $itemSeller->id : null;

        $fromUserItemPivot = null;

        if($itemBuyerId){
            $fromUserItemPivotQuery = $item->purchasedByUsers()
                ->where('users.id', $itemBuyerId)
                ->first();
            $fromUserItemPivot = $fromUserItemPivotQuery ? $fromUserItemPivotQuery->pivot : null;
        }//$itemBuyerId

        DB::transaction(function () use (
            $fromUserItemPivot, 
            $fromUserId,
            $itemId, 
            $itemBuyerId,
            $itemSellerId
        ) {
            $fromUser = User::find($fromUserId);
            $item = Item::find($itemId);
            $itemSeller = User::find($itemSellerId);
            $itemBuyer = User::find($itemBuyerId);

            if($fromUserItemPivot&&$fromUser){
                // 購入者側
                if ($fromUserId === $itemBuyerId) {
                    $fromUserItemPivot->is_buyer_completed = true;
                }
                // 出品者側
                if ($fromUserId === $itemSellerId) {
                    $fromUserItemPivot->is_seller_completed = true;
                }
                // 両方完了したら取引完了
                if ($fromUserItemPivot->is_buyer_completed && $fromUserItemPivot->is_seller_completed) {
                    Mail::to($itemSeller->email)->send(new TransactionCompletedMail($item, $itemSeller));
                    $fromUserItemPivot->status = UserItemStatus::COMPLETED;
                    $fromUserItemPivot->completed_at = now();
                }
                $fromUserItemPivot->save();
            }//$fromUserItemPivot
        });
    }//completeAfterRatingFromItemIdAndUserId

    public function ratingStoreItemId(RatingRequest $request,$item_id){

        $originalRatingValue = $request->rating_value;
        $ratingValue = $originalRatingValue ? $originalRatingValue : 0;
        $itemId = (int)$item_id;

        $authUser = Auth::user();
        $authUserId = null;
        if($authUser){
            $authUserId = $authUser->id;
        }//$authUser

        $ratingDTO = RatingService::getRatingDTOFromItemIdAndUserId($itemId,$authUserId);
        self::updateRatingFromRatingDTO($ratingDTO,$ratingValue);
        self::completeAfterRatingFromItemIdAndUserId($itemId,$authUserId);

        $returnedRoute = route("index");
        return redirect($returnedRoute)->with("ratingBarStore","success");
    }
}
