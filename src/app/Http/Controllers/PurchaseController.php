<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Checkout\Session as CheckoutSession;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use App\Models\PurchaseMethod;
use Illuminate\Support\Facades\Auth;
use App\Constants\UserItemStatus;

class PurchaseController extends Controller
{
    public const UNDEFINED_PIVOT_COMPOSE_STATUS = 0;
    public const CREATE_PIVOT_COMPOSE_STATUS = 1;
    public const UPDATE_PIVOT_COMPOSE_STATUS = 2;
    public const DEFAULT_PURCHASE_QUANTITY = 1;

    public const NEW_ITEM_PURCHASE_KIND = 1;
    public const NEW_ADDRESS_PURCHASE_KIND = 2;
    public const NEW_PURCHASE_METHOD_ID_PURCHASE_KIND = 3;

    public function purchaseTypedTableChange(Request $request,$itemId,$purchaseKind){

        $newItemPurchaseKind = self::NEW_ITEM_PURCHASE_KIND;
        $newAddressPurchaseKind = self::NEW_ADDRESS_PURCHASE_KIND;
        $newPurchaseMethodIdPurchaseKind = self::NEW_PURCHASE_METHOD_ID_PURCHASE_KIND;
        $defaultPurchaseQuantity = self::DEFAULT_PURCHASE_QUANTITY;
        $undefinedPivotComposeStatus = self::UNDEFINED_PIVOT_COMPOSE_STATUS;
        $createPivotComposeStatus = self::CREATE_PIVOT_COMPOSE_STATUS;
        $updatePivotComposeStatus = self::UPDATE_PIVOT_COMPOSE_STATUS;
        $authenticatedUser = Auth::user();
        $selectedItem = Item::findOrFail($itemId);

        if($authenticatedUser){
            $isFilledWithFrofile = $authenticatedUser->is_filled_with_profile;
            if($selectedItem){
                $selectedPendingTypedPivot = $authenticatedUser->pendingItems()
                    ->wherePivot('item_id', $itemId)
                    ->wherePivot('type', 'purchase')
                    ->latest('created_at')
                    ->first();
            }else{
                $selectedPendingTypedPivot = null;
            }
        }else{
            $isFilledWithFrofile = false;
            $selectedPendingTypedPivot = null;
        }

        if($purchaseKind === $newItemPurchaseKind){
            if($selectedPendingTypedPivot){
                $pivotComposeStatus = $updatePivotComposeStatus;
            }else{
                $pivotComposeStatus = $createPivotComposeStatus;
            }
        }else if($purchaseKind === $newAddressPurchaseKind){
            if($selectedPendingTypedPivot){
                $pivotComposeStatus = $updatePivotComposeStatus;
            }else{
                $pivotComposeStatus = $createPivotComposeStatus;
            }
        }else if($purchaseKind === $newPurchaseMethodIdPurchaseKind){
            if($selectedPendingTypedPivot){
                $pivotComposeStatus = $updatePivotComposeStatus;
            }else{
                $pivotComposeStatus = $createPivotComposeStatus;
            }
        }else{//$purchaseKind
            $pivotComposeStatus = $undefinedPivotComposeStatus;
        }//$purchaseKind

        $originalType = 'purchase';
        $originalStatus = UserItemStatus::PENDING;

        $originalPurchaseQuantity = 0;
        $originalPriceAtPurchase = 0;
        $originalPurchasedAt = null;
        $originalPurchaseMethodId = null;
        $originalIsFilledWithDeliveryAddress = false;
        $originalDeliveryPostcode = null;
        $originalDeliveryAddress = null;
        $originalDeliveryBuilding = null;
        $originalIsBuyerCompleted = false;
        $originalIsSellerCompleted = false;
        $originalCompletedAt = null;

        if($pivotComposeStatus === $updatePivotComposeStatus){
            if($selectedPendingTypedPivot->pivot){
                $originalType = $selectedPendingTypedPivot->pivot->type;
                $originalPurchaseQuantity = $selectedPendingTypedPivot->pivot->purchase_quantity;
                $originalPriceAtPurchase = $selectedPendingTypedPivot->pivot->price_at_purchase;
                $originalPurchasedAt = $selectedPendingTypedPivot->pivot->purchased_at;
                $originalPurchaseMethodId = $selectedPendingTypedPivot->pivot->purchase_method_id;
                $originalIsFilledWithDeliveryAddress = $selectedPendingTypedPivot->pivot->is_filled_with_delivery_address;
                $originalDeliveryPostcode = $selectedPendingTypedPivot->pivot->delivery_postcode;
                $originalDeliveryAddress = $selectedPendingTypedPivot->pivot->delivery_address;
                $originalDeliveryBuilding = $selectedPendingTypedPivot->pivot->delivery_building;
                $originalStatus = $selectedPendingTypedPivot->pivot->status;
                $originalIsBuyerCompleted = $selectedPendingTypedPivot->pivot->is_buyer_completed;
                $originalIsSellerCompleted = $selectedPendingTypedPivot->pivot->is_seller_completed;
                $originalCompletedAt = $selectedPendingTypedPivot->pivot->completed_at;
            }//$selectedPendingTypedPivot->pivot
        }//$pivotComposeStatus

        if($purchaseKind === $newItemPurchaseKind){
            $nextType = $originalType;
            $nextPurchaseQuantity = $defaultPurchaseQuantity;
            $nextPriceAtPurchase = $selectedItem->price;
            $nextPurchasedAt = now();
            $nextPurchaseMethodId = $request->input('purchase_method_id');
            $nextIsFilledWithDeliveryAddress = true;
            $nextDeliveryPostcode = $request->input('delivery_postcode');
            $nextDeliveryAddress = $request->input('delivery_address');
            $nextDeliveryBuilding = $request->input('delivery_building');
            $nextStatus = UserItemStatus::TRADING;
            $nextIsBuyerCompleted = $originalIsBuyerCompleted;
            $nextIsSellerCompleted = $originalIsSellerCompleted;
            $nextCompletedAt = $originalCompletedAt;
        }else if($purchaseKind === $newAddressPurchaseKind){
            $nextType = $originalType;
            $nextPurchaseQuantity = $originalPurchaseQuantity;
            $nextPriceAtPurchase = $originalPriceAtPurchase;
            $nextPurchasedAt = $originalPurchasedAt;
            $nextPurchaseMethodId = $originalPurchaseMethodId;
            $nextIsFilledWithDeliveryAddress = true;
            $nextDeliveryPostcode = $request->input('delivery_postcode');
            $nextDeliveryAddress = $request->input('delivery_address');
            $nextDeliveryBuilding = $request->input('delivery_building');
            $nextStatus = $originalStatus;
            $nextIsBuyerCompleted = $originalIsBuyerCompleted;
            $nextIsSellerCompleted = $originalIsSellerCompleted;
            $nextCompletedAt = $originalCompletedAt;
        }else if($purchaseKind === $newPurchaseMethodIdPurchaseKind){
            $nextType = $originalType;
            $nextPurchaseQuantity = $originalPurchaseQuantity;
            $nextPriceAtPurchase = $originalPriceAtPurchase;
            $nextPurchasedAt = $originalPurchasedAt;
            $nextPurchaseMethodId = $request->input('purchase_method_id');
            $nextIsFilledWithDeliveryAddress = $request->input('is_filled_with_delivery_address');
            $nextDeliveryPostcode = $request->input('delivery_postcode');
            $nextDeliveryAddress = $request->input('delivery_address');
            $nextDeliveryBuilding = $request->input('delivery_building');
            $nextStatus = $originalStatus;
            $nextIsBuyerCompleted = $originalIsBuyerCompleted;
            $nextIsSellerCompleted = $originalIsSellerCompleted;
            $nextCompletedAt = $originalCompletedAt;
        }else{//$purchaseKind
            $nextType = $originalType;
            $nextPurchaseQuantity = $originalPurchaseQuantity;
            $nextPriceAtPurchase = $originalPriceAtPurchase;
            $nextPurchasedAt = $originalPurchasedAt;
            $nextPurchaseMethodId = $originalPurchaseMethodId;
            $nextIsFilledWithDeliveryAddress = $originalIsFilledWithDeliveryAddress;
            $nextDeliveryPostcode = $originalDeliveryPostcode;
            $nextDeliveryAddress = $originalDeliveryAddress;
            $nextDeliveryBuilding = $originalDeliveryBuilding;
            $nextStatus = $originalStatus;
            $nextIsBuyerCompleted = $originalIsBuyerCompleted;
            $nextIsSellerCompleted = $originalIsSellerCompleted;
            $nextCompletedAt = $originalCompletedAt;
        }//$purchaseKind


        $nextSelectedPendingTypedPivot = [
            'type' => $nextType,
            'purchase_quantity' => $nextPurchaseQuantity,
            'price_at_purchase' => $nextPriceAtPurchase,
            'purchased_at' => $nextPurchasedAt,
            'purchase_method_id' => $nextPurchaseMethodId,
            'is_filled_with_delivery_address' => $nextIsFilledWithDeliveryAddress,
            'delivery_postcode' => $nextDeliveryPostcode,
            'delivery_address'  => $nextDeliveryAddress,
            'delivery_building' => $nextDeliveryBuilding,
            'status' => $nextStatus,
            'is_buyer_completed' => $nextIsBuyerCompleted,
            'is_seller_completed' => $nextIsSellerCompleted,
            'completed_at' => $nextCompletedAt,
        ];

        if ($pivotComposeStatus === $updatePivotComposeStatus) {
            $authenticatedUser->pendingItems()->updateExistingPivot($itemId, $nextSelectedPendingTypedPivot);
        }else if($pivotComposeStatus === $createPivotComposeStatus){
            $authenticatedUser->pendingItems()->attach($itemId, $nextSelectedPendingTypedPivot);
        }

        return($nextSelectedPendingTypedPivot);

    }//purchaseTypedTableChange

    public function onPurchase(Request $request,$purchaseKind,$itemId,$ajax=false){

        $newItemPurchaseKind = self::NEW_ITEM_PURCHASE_KIND;
        $newAddressPurchaseKind = self::NEW_ADDRESS_PURCHASE_KIND;
        $newPurchaseMethodIdPurchaseKind = self::NEW_PURCHASE_METHOD_ID_PURCHASE_KIND;

        $selectedItem = Item::findOrFail($itemId);
        $authenticatedUser = Auth::user();
        if (!$authenticatedUser) {
            if ($ajax) {
                return null; // Ajax 用は JSON 側で返すので redirect は不要
            }
            abort(403, 'ログインしてください');
        }

        if($purchaseKind === $newItemPurchaseKind){
            $candidateRedirectedRoute = route('index');
        }else if($purchaseKind === $newAddressPurchaseKind){
            $candidateRedirectedRoute = route('purchase.itemId', ['item_id' => $itemId]);
        }else if($purchaseKind === $newPurchaseMethodIdPurchaseKind){
            $candidateRedirectedRoute = route('purchase.itemId', ['item_id' => $itemId]);
        }else{//$purchaseKind
            $candidateRedirectedRoute = route('index');
        }//$purchaseKind

        $nextSelectedPendingTypedPivot = $this->purchaseTypedTableChange($request,$itemId,$purchaseKind);
        $newPurchaseQuantity = $nextSelectedPendingTypedPivot["purchase_quantity"];

        if ($ajax) {
            return null;
        }

        $requestedPurchaseMethodId = null;
        if($purchaseKind === $newItemPurchaseKind){
            if($request->has('purchase_method_id')){
                $requestedPurchaseMethodId = $request->input('purchase_method_id');
            }
        }
        
        $purchaseMethodName = null;
        if($requestedPurchaseMethodId){
            $purchaseMethodName = PurchaseMethod::find($requestedPurchaseMethodId)?->name;
        }

        if($purchaseMethodName === 'コンビニ払い'){
            $selectedStringPaymentMethod = 'konbini';
        }else if($purchaseMethodName === 'カード支払い'){
            $selectedStringPaymentMethod = 'card';
        }else{
            $selectedStringPaymentMethod = null;
        }

        $stripeScreenDenialMarker = 0;
        if(!$selectedStringPaymentMethod){
            $stripeScreenDenialMarker = 1;
        }

        if($stripeScreenDenialMarker === 0){

           $session = CheckoutSession::create([
                'payment_method_types' => [$selectedStringPaymentMethod],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => ['name' => $selectedItem->name],
                        'unit_amount' => $selectedItem->price,
                    ],
                    'quantity' => $newPurchaseQuantity,
                ]],
                'mode' => 'payment',
                'success_url' => route('index'),
                'cancel_url' => route('index'),
            ]);

            $redirectedRoute = $session->url ?? $candidateRedirectedRoute;
        }else{//$stripeScreenDenialMarker
            $session = null;
            $redirectedRoute = $candidateRedirectedRoute;
        }//$stripeScreenDenialMarker

        return redirect($redirectedRoute);

    }//onPurchase

    public function purchaseStoreItemId(PurchaseRequest $request,$item_id){

        $itemId = (int)$item_id;
        $purchaseKind = self::NEW_ITEM_PURCHASE_KIND;
        return($this->onPurchase($request,$purchaseKind,$itemId));
    }//store

    public function purchaseAddressUpdateItemId(AddressRequest $request,$item_id){

        $itemId = (int)$item_id;
        $purchaseKind = self::NEW_ADDRESS_PURCHASE_KIND;

        return($this->onPurchase($request,$purchaseKind,$itemId));
    }//store

    public function purchaseUpdateMethodItemId(Request $request, $item_id)
    {
        $itemId = (int)$item_id;

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'ログインしてください'], 401);
        }

        $purchaseKind = self::NEW_PURCHASE_METHOD_ID_PURCHASE_KIND;

        // onPurchase を呼ぶが、Ajaxの場合は redirect を返さないようにフラグを渡す
        $result = $this->onPurchase($request, $purchaseKind, $itemId, $ajax = true);

        return response()->json(['success' => true, 'message' => '購入方法を更新しました']);
    }

    

}
