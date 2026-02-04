<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\PurchaseMethod;
use App\Models\Rating;
use App\Models\User;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use App\Constants\ShowFunctionKinds\OriginalShowFunctionKind;
use App\Constants\ShowFunctionKinds\ShowFunctionKind;
use App\Constants\ShownItemsKind;
use App\Constants\PreviewErrorStatus;
use App\Constants\PreviewPostType;
use App\Constants\TransactionCommentStatus;
use App\Services\ShownItemsService;
use App\Services\RatingService;
use App\Services\TransactionCommentService;

class ShowController extends Controller
{
    public static function getShowFunctionKind(Request $request,$originalShowFunctionKind){
        $showFunctionKind = ShowFunctionKind::UNDEFINED;
        if($originalShowFunctionKind === OriginalShowFunctionKind::LOGIN){
            $showFunctionKind = ShowFunctionKind::LOGIN;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::REGISTER){
            $showFunctionKind = ShowFunctionKind::REGISTER;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::SHOW_EMAIL_VERIFICATION){
            $showFunctionKind = ShowFunctionKind::SHOW_EMAIL_VERIFICATION;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::INDEX){
            $mode = $request->query('tab', 'index');
            if($mode == 'index'){
                $showFunctionKind = ShowFunctionKind::INDEX_INDEX;
            }else if($mode == 'mylist'){
                $showFunctionKind = ShowFunctionKind::MY_LIST_INDEX;
            }else{
                $showFunctionKind = ShowFunctionKind::INDEX_INDEX;
            }//$mode
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::MYPAGE){
            $mode = $request->query('page', 'sell');
            if($mode === 'sell'){
                $showFunctionKind = ShowFunctionKind::SOLD_GOODS_MYPAGE;
            }else if($mode === 'buy'){
                $showFunctionKind = ShowFunctionKind::BOUGHT_GOODS_MYPAGE;
            }else if($mode === 'deal'){
                $showFunctionKind = ShowFunctionKind::DEAL_GOODS_MYPAGE;
            }else{
                $showFunctionKind = ShowFunctionKind::SOLD_GOODS_MYPAGE;
            }
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::SELL){
            $showFunctionKind = ShowFunctionKind::SELL;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::ITEM_EDIT_ITEM_ID){
            $showFunctionKind = ShowFunctionKind::ITEM_EDIT_ITEM_ID;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::ITEM_ITEM_ID){
            $showFunctionKind = ShowFunctionKind::ITEM_ITEM_ID;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::PURCHASE_ITEM_ID){
            $showFunctionKind = ShowFunctionKind::PURCHASE_ITEM_ID;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::PURCHASE_ADDRESS_ITEM_ID){
            $showFunctionKind = ShowFunctionKind::PURCHASE_ADDRESS_ITEM_ID;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::MYPAGE_PROFILE){
            $showFunctionKind = ShowFunctionKind::MYPAGE_PROFILE;
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::ITEM_DEAL_ITEM_ID){
            $showFunctionKind = ShowFunctionKind::ITEM_DEAL_ITEM_ID;
        }//$originalShowFunctionKind
        return($showFunctionKind);
    }//getShowFunctionKind

    public static function getShowFunctionProperties($showFunctionKind){
        if($showFunctionKind === ShowFunctionKind::LOGIN){
            $returnedViewFile = 'auth.login';
            $selectedItemMakeMarker = 0;
            $isMultipleFunctionHeader = true;
            $shownItemsKind = ShownItemsKind::UNDEFINED;
        }else if($showFunctionKind === ShowFunctionKind::REGISTER){
            $returnedViewFile = 'auth.register';
            $selectedItemMakeMarker = 0;
            $isMultipleFunctionHeader = true;
            $shownItemsKind = ShownItemsKind::UNDEFINED;
        }else if($showFunctionKind === ShowFunctionKind::SHOW_EMAIL_VERIFICATION){
            $returnedViewFile = 'auth.verify-email';
            $selectedItemMakeMarker = 0;
            $isMultipleFunctionHeader = true;
            $shownItemsKind = ShownItemsKind::UNDEFINED;
        }else if($showFunctionKind === ShowFunctionKind::INDEX_INDEX){
            $returnedViewFile = 'index';
            $selectedItemMakeMarker = 0;
            $isMultipleFunctionHeader = true;
            $shownItemsKind = ShownItemsKind::OTHER_USERS_GOODS;
        }else if($showFunctionKind === ShowFunctionKind::MY_LIST_INDEX){
            $returnedViewFile = 'index';
            $selectedItemMakeMarker = 0;
            $isMultipleFunctionHeader = true;
            $shownItemsKind = ShownItemsKind::FAVORITE_GOODS;
        }else if($showFunctionKind === ShowFunctionKind::SOLD_GOODS_MYPAGE){
            $returnedViewFile = 'index';
            $selectedItemMakeMarker = 0;
            $isMultipleFunctionHeader = true;
            $shownItemsKind = ShownItemsKind::SOLD_GOODS;
        }else if($showFunctionKind === ShowFunctionKind::BOUGHT_GOODS_MYPAGE){
            $returnedViewFile = 'index';
            $selectedItemMakeMarker = 0;
            $isMultipleFunctionHeader = true;
            $shownItemsKind = ShownItemsKind::BOUGHT_GOODS;
        }else if($showFunctionKind === ShowFunctionKind::DEAL_GOODS_MYPAGE){
            $returnedViewFile = 'index';
            $selectedItemMakeMarker = 0;
            $isMultipleFunctionHeader = true;
            $shownItemsKind = ShownItemsKind::DEAL_GOODS;
        }else if($showFunctionKind === ShowFunctionKind::SELL){
            $returnedViewFile = 'sell';
            $selectedItemMakeMarker = 0;
            $isMultipleFunctionHeader = true;
            $shownItemsKind = ShownItemsKind::UNDEFINED;
        }else if($showFunctionKind === ShowFunctionKind::ITEM_EDIT_ITEM_ID){
            $returnedViewFile = 'sell';
            $selectedItemMakeMarker = 1;
            $isMultipleFunctionHeader = true;
            $shownItemsKind = ShownItemsKind::UNDEFINED;
        }else if($showFunctionKind === ShowFunctionKind::ITEM_ITEM_ID){
            $returnedViewFile = 'evaluation';
            $selectedItemMakeMarker = 2;
            $isMultipleFunctionHeader = true;
            $shownItemsKind = ShownItemsKind::UNDEFINED;
        }else if($showFunctionKind === ShowFunctionKind::PURCHASE_ITEM_ID){
            $returnedViewFile = 'purchase';
            $selectedItemMakeMarker = 3;
            $isMultipleFunctionHeader = true;
            $shownItemsKind = ShownItemsKind::UNDEFINED;
        }else if($showFunctionKind === ShowFunctionKind::PURCHASE_ADDRESS_ITEM_ID){
            $returnedViewFile = 'address';
            $selectedItemMakeMarker = 4;
            $isMultipleFunctionHeader = true;
            $shownItemsKind = ShownItemsKind::UNDEFINED;
        }else if($showFunctionKind === ShowFunctionKind::MYPAGE_PROFILE){
            $returnedViewFile = 'profile';
            $selectedItemMakeMarker = 0;
            $isMultipleFunctionHeader = true;
            $shownItemsKind = ShownItemsKind::UNDEFINED;
        }else if($showFunctionKind === ShowFunctionKind::ITEM_DEAL_ITEM_ID){
            $returnedViewFile = 'deal';
            $selectedItemMakeMarker = 1;
            $isMultipleFunctionHeader = true;
            $shownItemsKind = ShownItemsKind::UNDEFINED;
        }else{//$showFunctionKind
            $returnedViewFile = 'index';
            $selectedItemMakeMarker = 0;
            $isMultipleFunctionHeader = false;
            $shownItemsKind = ShownItemsKind::UNDEFINED;
        }//$showFunctionKind

        $showFunctionProperties = [
            "returnedViewFile" => $returnedViewFile,
            "selectedItemMakeMarker" => $selectedItemMakeMarker,
            "isMultipleFunctionHeader" => $isMultipleFunctionHeader,
            "shownItemsKind" => $shownItemsKind,
        ];

        return($showFunctionProperties);

    }//getShowFunctionProperties

    

    public function onCreate(Request $request,$originalShowFunctionKind,$itemId){

        $authUser = Auth::user();
        $authUserId = $authUser ? $authUser->id : null;
        $authUserIdCoincidence = false;
        $showFunctionKind = self::getShowFunctionKind($request,$originalShowFunctionKind);

        $csrfToken = csrf_token();
        $openRatingModalButtonId = "open-rating-modal-button-id";
        $ratingModalId = "rating-modal-id";
        $ratingModalClass = "rating-modal";
        $ratingModalContentClass = "rating-modal-content";

        $editModalId = "edit-modal-id";
        $editModalClass = "edit-modal";
        $editModalContentClass = "edit-modal-content";
        $openEditButtonClass = "open-edit-button";
        $closeEditModalButtonId = "close-edit-modal-button-id";
        $editModalCommentId = "edit-modal-comment-id";
        $editModalMessageId = "edit-modal-message-id";
        $prefixPublishedTransactionCommentId = "comment-";

        $previewImageInputId = "preview-image-input-id";
        $previewGridId = "preview-grid-id";
        $previewRemoveButtonClass = "preview-remove-button";
        $previewCellClass = "preview-cell";
        $previewGridClass = "preview-grid";
        $previewCommentSendButtonId = "preview-comment-send-button-id";

        $previewPostTypes = PreviewPostType::toArray();
        $transactionCommentName = PreviewErrorStatus::TRANSACTION_COMMENT_NAME;

        $coachtechImageDirectory = BaseController::COACHTECH_IMAGE_DIRECTORY;
        $itemImageDirectory = BaseController::ITEM_IMAGE_DIRECTORY;
        $itemImagePrefix = BaseController::ITEM_IMAGE_PREFIX;
        $userImageDirectory = BaseController::USER_IMAGE_DIRECTORY;
        $defaultProfileImageDirectory = BaseController::DEFAULT_PROFILE_IMAGE_DIRECTORY;
        $defaultProfileImageName = BaseController::DEFAULT_PROFILE_IMAGE_NAME;
        $trashImageDirectory = BaseController::TRASH_IMAGE_DIRECTORY;
        $trashImageName = BaseController::TRASH_IMAGE_NAME;
        $userImagePrefix = BaseController::USER_IMAGE_PREFIX;

        $customSelectId = "custom-select-id";

        $routePurchaseUpdateMethodItemId = null;
        $routeTransactionCommentUpdateItemId = null;
        if($itemId){
            $routePurchaseUpdateMethodItemId = route("purchase.updateMethod.itemId", $itemId);
            $routeTransactionCommentUpdateItemId = route("transactionCommentUpdate.itemId",$itemId);
        }//$itemId
        
        $defaultProfilePreviewUrl = asset('storage/'.$defaultProfileImageDirectory.'/'.$defaultProfileImageName);
        $trashPreviewUrl = asset('storage/'.$trashImageDirectory.'/'.$trashImageName);

        if($authUser){
            $authUserImageName = $authUser->image;
            $isFilledWithProfile = $authUser->is_filled_with_profile;
        }else{
            $authUserImageName = null;
            $isFilledWithProfile = false;
        }

        $keyword = $request->input('keyword');

        if ($request->has('keyword')) {
            if ($keyword === '' || $keyword === null) {
                session()->forget('search_keyword');
            } else {
                session(['search_keyword' => $keyword]);
            }
        } else {
            $keyword = session('search_keyword', '');
        }

        $categories = Category::all();
        $conditions = Condition::all();
        $purchaseMethods = PurchaseMethod::all();

        $showFunctionProperties = self::getShowFunctionProperties($showFunctionKind);

        $returnedViewFile = $showFunctionProperties["returnedViewFile"];
        $selectedItemMakeMarker = $showFunctionProperties["selectedItemMakeMarker"];
        $isMultipleFunctionHeader = $showFunctionProperties["isMultipleFunctionHeader"];
        $shownItemsKind = $showFunctionProperties["shownItemsKind"];

        $shownItems = ShownItemsService::getShownItems($authUser,$keyword,$shownItemsKind);


        if($selectedItemMakeMarker == 0){
            $selectedItem = null;
        }else{
            $selectedItem = Item::find($itemId);
        }

        $categoryButtonAppendingClass = "active";
        $selectedItemId = null;
        $draftTransactionComment = null;
        $publishedTransactionComments = null;
        $selectedItemRatingDTO = null;
        $selectedItemRatingId = null;
        $selectedItemRating = null;
        $selectedItemRatingRatingValue = 0;
        $selectedItemSeller = null;
        $selectedItemBuyer = null;
        $selectedItemSellerName = null;
        $selectedItemSellerId = null;
        $selectedItemBuyerName = null;
        $selectedItemBuyerId = null;
        $counterpartUserId = null;
        $counterpartUser = null;
        $selectedItemCommentNumber = 0;
        $selectedUserIds = null;
        $selectedCategoryIds = null;
        $selectedConditionId = null;
        $selectedItemHasBuyerRated = false;
        $isPurchased = false;
        $owners = null;
        $selectedPendingTypedPivot = null;
        $isOwner = false;
        $isPurchasedBy = false;
        $selectedPurchaseMethodId = null;
        $selectedFavoritedUsers = null;
        $selectedCommentDescriptions = null;
        $selectedCategories = null;
        $selectedCondition = null;

        $authUserMaxRatingNumber = 0;
        $authUserTotalRatingValue = 0;
        $authUserRoundedRatingValue = 0;
        if($authUser){
            $authUserMaxRatingNumber = Rating::where('to_user_id', $authUserId)->count();
            $authUserTotalRatingValue = Rating::where('to_user_id', $authUserId)->sum('rating_value');
            if($authUserMaxRatingNumber <= 0){
                $authUserRoundedRatingValue = 0;
            }else{//$authUserMaxRatingNumber
                $authUserRoundedRatingValue = round(((double)$authUserTotalRatingValue / (double)$authUserMaxRatingNumber));
            }//$authUserMaxRatingNumber
        }

        if($selectedItem){
            $selectedItemId = $selectedItem->id;
            $selectedItemRatingDTO = RatingService::getRatingDTOFromUserIdAndItemId(
                $authUserId,
                $selectedItemId
            );

            $draftTransactionComment = TransactionCommentService::getDraftTransactionCommentFromUserIdAndItemId(
                $authUserId,
                $selectedItemId
            );

            $publishedTransactionComments = TransactionCommentService::getPublishedTransactionCommentsFromUserIdAndItemId(
                $authUserId,
                $selectedItemId
            );

            $selectedItemRatingId = $selectedItemRatingDTO->rating_id;
            $selectedItemRating = Rating::find($selectedItemRatingId);
            if($selectedItemRating){
                $selectedItemRatingRatingValue = $selectedItemRating->rating_value;
            }//$selectedItemRating

            $selectedItemSeller = $selectedItem->seller();
            $selectedItemBuyer = $selectedItem->buyer();
            if($selectedItemSeller){
                $selectedItemSellerName = $selectedItemSeller->name;
                $selectedItemSellerId = $selectedItemSeller->id;
            }//$selectedItemSeller

            if($selectedItemBuyer){
                $selectedItemBuyerName = $selectedItemBuyer->name;
                $selectedItemBuyerId = $selectedItemBuyer->id;
            }//$selectedItemBuyer

            if($authUserId === $selectedItemBuyerId){
                $counterpartUserId = $selectedItemSellerId;
            }else if($authUserId === $selectedItemSellerId){
                $counterpartUserId = $selectedItemBuyerId;
            }

            $counterpartUser = User::find($counterpartUserId);



            $selectedUserIds = $selectedItem->usersByOwnership->pluck('id')->toArray();
            $selectedCategoryIds = $selectedItem->categories->pluck('id')->toArray();
            $selectedConditionId = $selectedItem->condition->id ?? null;
            $isPurchased = $selectedItem->isPurchased();
            if($selectedItem->comments){
                $selectedItemCommentNumber = $selectedItem->comments->count();
            }else{
                $selectedItemCommentNumber = 0;
            }
            $owners = $selectedItem->usersByOwnership;
            
            if ($authUser) {
                $selectedPendingTypedPivot = $authUser->pendingItems()
                    ->wherePivot('item_id', $itemId)
                    ->wherePivot('type', 'purchase')
                    ->first();

                $isOwner = $owners->contains($authUserId);
                $isPurchasedBy = $selectedItem->isPurchasedBy($authUser);
                $selectedPurchaseMethodId = optional(
                    $selectedItem->purchasedByUsers->firstWhere('id', $authUserId)
                )->pivot->purchase_method_id ?? null;
            }else{
                $selectedPendingTypedPivot = null;
                $isOwner = false;
                $isPurchasedBy = false;
                $selectedPurchaseMethodId = null;
            }

            $selectedFavoritedUsers = $selectedItem->favoritedByUsers;
            $selectedCommentDescriptions = $selectedItem->comments->pluck('description')->toArray();
            $selectedCategories = Category::whereIn('id', $selectedCategoryIds)->get();
            $selectedCondition = Condition::findOrFail($selectedConditionId);
            $selectedItemHasBuyerRated = $selectedItem->hasBuyerRated();

        }
        
        if($selectedUserIds){
            if (in_array($authUserId, $selectedUserIds)) {
                $authUserIdCoincidence = true;
            }
        }//$selectedUserIds

        return view($returnedViewFile,compact(
            "csrfToken",
            "openRatingModalButtonId",
            "ratingModalId",
            "ratingModalClass",
            "ratingModalContentClass",

            "editModalId",
            "editModalClass",
            "editModalContentClass",
            "openEditButtonClass",
            "closeEditModalButtonId",
            "editModalCommentId",
            "editModalMessageId",
            "prefixPublishedTransactionCommentId",

            'previewImageInputId',
            'previewGridId',
            'previewRemoveButtonClass',
            'previewCellClass',
            'previewGridClass',
            'previewCommentSendButtonId',
            'transactionCommentName',

            'itemImageDirectory',
            'itemImagePrefix',
			'userImageDirectory',
            'userImagePrefix',
            'coachtechImageDirectory',
            'categoryButtonAppendingClass',
            'routePurchaseUpdateMethodItemId',
            'routeTransactionCommentUpdateItemId',
            'customSelectId',
            'previewPostTypes',

            'showFunctionKind',
            'isMultipleFunctionHeader',
            'defaultProfilePreviewUrl',
            'trashPreviewUrl',
            'authUser',
            'authUserId',
            'authUserIdCoincidence',
            'authUserImageName',
            'authUserMaxRatingNumber',
            'authUserRoundedRatingValue',
            'isFilledWithProfile',
            'itemId',
            'categories',
            'conditions',
            'purchaseMethods',
            'shownItems',
            'selectedItem',
            'selectedItemId',
            'selectedItemRatingRatingValue',
            'selectedItemSeller',
            'selectedItemSellerId',
            'selectedItemSellerName',
            'selectedItemBuyer',
            'selectedItemBuyerId',
            'selectedItemBuyerName',
            'counterpartUser',
            'counterpartUserId',
            'selectedItemCommentNumber',
            'selectedItemHasBuyerRated',
            'selectedCategoryIds',
            'selectedConditionId',
            'draftTransactionComment',
            'publishedTransactionComments',
            'isPurchased',
            'isPurchasedBy',
            'isOwner',
            'selectedPurchaseMethodId',
            'selectedPendingTypedPivot',
            'selectedFavoritedUsers',
            'selectedCommentDescriptions',
            'selectedCategories',
            'selectedCondition',
        ));
        
    }//onCreate

    public function login(Request $request) {
        $itemId = null;
        $originalShowFunctionKind = OriginalShowFunctionKind::LOGIN;
        return $this->onCreate($request,$originalShowFunctionKind,$itemId);
    }

    public function register(Request $request) {
        $itemId = null;
        $originalShowFunctionKind = OriginalShowFunctionKind::REGISTER;
        return $this->onCreate($request,$originalShowFunctionKind,$itemId);
    }

    public function showEmailVerification(Request $request)
    {
        $itemId = null;
        $originalShowFunctionKind = OriginalShowFunctionKind::SHOW_EMAIL_VERIFICATION;
        return $this->onCreate($request,$originalShowFunctionKind,$itemId);
    }

    public function index(Request $request)
    {
        $itemId = null;
        $originalShowFunctionKind = OriginalShowFunctionKind::INDEX;
        return $this->onCreate($request,$originalShowFunctionKind,$itemId);
    }


    public function mypage(Request $request)
    {
        $itemId = null;
        $originalShowFunctionKind = OriginalShowFunctionKind::MYPAGE;
        return $this->onCreate($request,$originalShowFunctionKind,$itemId);
    }


    public function sell(Request $request) {
        $itemId = null;
        $originalShowFunctionKind = OriginalShowFunctionKind::SELL;
        return $this->onCreate($request,$originalShowFunctionKind,$itemId);
    }

    public function itemEditItemId(Request $request,$item_id = null){
        $itemId = (int)$item_id;
        $originalShowFunctionKind = OriginalShowFunctionKind::ITEM_EDIT_ITEM_ID;
        return $this->onCreate($request,$originalShowFunctionKind,$itemId);
    }

    public function itemItemId(Request $request,$item_id = null){
        $itemId = (int)$item_id;
        $originalShowFunctionKind = OriginalShowFunctionKind::ITEM_ITEM_ID;
        return $this->onCreate($request,$originalShowFunctionKind,$itemId);
    }

    public function purchaseItemId(Request $request,$item_id = null){
        $itemId = (int)$item_id;
        $originalShowFunctionKind = OriginalShowFunctionKind::PURCHASE_ITEM_ID;
        return $this->onCreate($request,$originalShowFunctionKind,$itemId);
    }

    public function purchaseAddressItemId(Request $request,$item_id = null){
        $itemId = (int)$item_id;
        $originalShowFunctionKind = OriginalShowFunctionKind::PURCHASE_ADDRESS_ITEM_ID;
        return $this->onCreate($request,$originalShowFunctionKind,$itemId);
    }

    public function mypageProfile(Request $request){
        $itemId = null;
        $originalShowFunctionKind = OriginalShowFunctionKind::MYPAGE_PROFILE;
        return $this->onCreate($request,$originalShowFunctionKind,$itemId);
    }

    public function itemDealItemId(Request $request,$item_id = null){
        $itemId = (int)$item_id;
        $originalShowFunctionKind = OriginalShowFunctionKind::ITEM_DEAL_ITEM_ID;
        return $this->onCreate($request,$originalShowFunctionKind,$itemId);
    }
}
