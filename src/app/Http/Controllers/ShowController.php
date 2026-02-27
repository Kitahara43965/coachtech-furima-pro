<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\PurchaseMethod;
use App\Models\Rating;
use App\Models\User;
use App\Models\UserItem;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Constants\Files\FileName;
use App\Constants\ShowFunctionKinds\OriginalShowFunctionKind;
use App\Constants\ShowFunctionKinds\ShowFunctionKind;
use App\Constants\ShownItemsKind;
use App\Constants\PreviewErrorStatus;
use App\Constants\PreviewPostType;
use App\Constants\UserKind;
use App\Constants\TransactionCommentStatus;
use App\Constants\NamePrefix;
use App\Services\ShownItemsService;
use App\Services\RatingService;
use App\Services\TransactionCommentService;
use App\Services\TransactionCommentDTOService;
use App\Constants\Files\PhpIniArgumentName;
use App\Services\Settings\PhpIniService;
use App\Services\NotifiedTransactionCommentService;
use App\Services\FileService;
use App\Services\UserDTOService;
use App\Services\TradingItemsService;


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
            $mode = $request->query('tab', null);
            if($mode == 'index'){
                $showFunctionKind = ShowFunctionKind::INDEX_INDEX;
            }else if($mode == 'mylist'){
                $showFunctionKind = ShowFunctionKind::MY_LIST_INDEX;
            }else{
                $showFunctionKind = ShowFunctionKind::INDEX_INDEX;
            }//$mode
        }else if($originalShowFunctionKind === OriginalShowFunctionKind::MYPAGE){
            $mode = $request->query('page', null);
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
            $isMultipleFunctionHeader = false;
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
        $ratingModalOpenButtonId = "rating-modal-open-button-id";
        $ratingModalId = "rating-modal-id";

        $editModalId = "edit-modal-id";
        $editModalClass = "edit-modal";
        $editModalContentClass = "edit-modal-content";
        $openEditButtonClass = "open-edit-button";
        $closeEditModalButtonId = "close-edit-modal-button-id";
        $editModalCommentId = "edit-modal-comment-id";
        $editModalMessageId = "edit-modal-message-id";
        $prefixPublishedTransactionCommentId = "comment-";

        $customSelectId = "custom-select-id";

        $dataFieldArgument = "data-field";
        $dataIdArgument = "data-id";
        $transactionCellContainerId = "transaction-cell-container-id";
        $transactionCellContainerClass = "transaction-cell-container";
        $transactionCellId = "transaction-cell-id";
        $transactionCellClass = "transaction-cell";
        $transactionErrorMessageId = "transaction-error-message-id";
        $transactionErrorMessageClass = "transaction-error-message";
        $transactionCommentCellIdPrefix = "transaction-comment-cell-id-";
        $transactionCommentCellClass = "transaction-comment-cell";
        $transactionCommentPreviewContainerIdPrefix = "transaction-comment-preview-container-id-";
        $transactionCommentPreviewContainerClass = "transaction-comment-preview-container";
        $transactionCommentPreviewIdPrefix = "transaction-comment-preview-id-";
        $transactionCommentPreviewClass = "transaction-comment-preview";
        $transactionCommentEditButtonContainerIdPrefix = "transaction-comment-edit-button-container-id-";
        $transactionCommentEditButtonContainerClass = "transaction-comment-edit-button-container";
        $transactionCommentEditButtonIdPrefix = "transaction-comment-edit-button-id-";
        $transactionCommentEditButtonClass = "transaction-comment-edit-button";
        $transactionCommentDeleteButtonIdPrefix = "transaction-comment-delete-button-id-";
        $transactionCommentDeleteButtonClass = "transaction-comment-delete-button";
        $transactionCommentUploadInputIdPrefix = "transaction-comment-upload-input-id-";
        $transactionCommentUploadInputClass = "transaction-comment-upload-input";
        $transactionCommentUploadLabelIdPrefix = "transaction-comment-upload-label-id-";
        $transactionCommentUploadLabelClass = "transaction-comment-upload-label";
        $transactionCommentSendButtonIdPrefix = "transaction-comment-send-button-id-";
        $transactionCommentSendButtonClass = "transaction-comment-send-button";
        $transactionCommentErrorMessageIdPrefix = "transaction-comment-error-message-id-";
        $transactionCommentErrorMessageClass = "transaction-comment-error-message";
        $transactionCommentErrorMessageNamePrefix = NamePrefix::TRANSACTION_COMMENT_ERROR_MESSAGE;
        $transactionCommentUserImagePreviewIdPrefix = "transaction-comment-user-image-container-id-";
        $transactionCommentUserImagePreviewClass = "transaction-comment-user-image-container";
        $transactionCommentCommentTextareaContainerIdPrefix = "transaction-comment-comment-textarea-container-id-";
        $transactionCommentCommentTextareaContainerClass = "transaction-comment-comment-textarea-container";
        $transactionCommentCommentTextareaIdPrefix = "transaction-comment-comment-textarea-id-";
        $transactionCommentCommentTextareaClass = "transaction-comment-comment-textarea";
        $transactionCommentCommentTextareaNamePrefix = NamePrefix::TRANSACTION_COMMENT_COMMENT_TEXTAREA;
        $transactionImageCellIdPrefix = "transaction-image-cell-id-";
        $transactionImageCellClass = "transaction-image-cell";
        $transactionImageRemoveButtonIdPrefix = "transaction-image-remove-button-id-";
        $transactionImageRemoveButtonClass = "transaction-image-remove-button";
        $transactionImagePreviewContainerIdPrefix = "transaction-image-preview-container-id-";
        $transactionImagePreviewContainerClass = "transaction-image-preview-container";
        $transactionImagePreviewIdPrefix = "transaction-image-preview-id-";
        $transactionImagePreviewClass = "transaction-image-preview";
        $transactionImageImageDivIdPrefix = "transaction-image-image-div-id-";
        $transactionImageImageDivClass = "transaction-image-image-div";
        $transactionImageErrorMessageIdPrefix = "transaction-image-error-message-id-";

        $userImageCellIdPrefix = "user-image-cell-id-";
        $userImageCellClass = "user-image-cell";
        $userImagePreviewContainerIdPrefix = "user-image-preview-container-id-";
        $userImagePreviewContainerClass = "user-image-preview-container";
        $userImagePreviewIdPrefix = "user-image-preview-id-";
        $userImagePreviewClass = "user-image-preview";
        $userImageImageDivIdPrefix = "user-image-image-div-id-";
        $userImageImageDivClass = "user-image-image-div";
        $userNameDivIdPrefix = "user-name-div-id-";
        $userNameDivClass = "user-name-div";

        $previewPostTypes = PreviewPostType::toArray();
        $userKinds = UserKind::toArray();
        $transactionCommentStatuses = TransactionCommentStatus::toArray();

        $phpIniArgumentNames = PhpIniArgumentName::toArray();
        $phpIniSettingSizesInBytes = PhpIniService::getPhpIniSettingSizesInBytes();

        $coachtechImageDirectory = FileName::COACHTECH_IMAGE_DIRECTORY;
        $itemImageDirectory = FileName::ITEM_IMAGE_DIRECTORY;
        $itemImagePrefix = FileName::ITEM_IMAGE_PREFIX;
        $userImageDirectory = FileName::USER_IMAGE_DIRECTORY;
        $trashImageDirectory = FileName::TRASH_IMAGE_DIRECTORY;
        $trashImageName = FileName::TRASH_IMAGE_NAME;
        $userImagePrefix = FileName::USER_IMAGE_PREFIX;

        $postedUser = $authUser;
        $postedUserId = $postedUser ? $postedUser->id : null;

        $routeLogin = route("login");
        $routeTransactionSend = route("transactionSend");

        $routePurchaseUpdateMethodItemId = null;
        $routeItemDealItemId = null;

        if($itemId){
            $routePurchaseUpdateMethodItemId = route("purchase.updateMethod.itemId", ['item_id'=>$itemId]);
            $routeItemDealItemId = route("item.deal.itemId",['item_id'=>$itemId]);
        }//$itemId


        $defaultProfileImageNamePath = FileService::getDefaultProfileImageNamePath();
        $defaultProfilePreviewUrl = asset('storage/'.$defaultProfileImageNamePath);
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

        $tradingItems = TradingItemsService::getTradingItemsCollectionFromUserIdAndKeyword(
            $postedUserId,
            $keyword)
        ;

        $notifiedProperties = NotifiedTransactionCommentService::
            getNotifiedPropertiesFromItemsAndUserId(
                $tradingItems,
                $postedUserId
            );

        $totalNotifiedTransactionCommentNumber = 0;
        $maxNotifiedTransactionCommentNumbers = null;
        if($notifiedProperties){
            $totalNotifiedTransactionCommentNumber
                = $notifiedProperties["totalNotifiedTransactionCommentNumber"];
            $maxNotifiedTransactionCommentNumbers
                = $notifiedProperties["maxNotifiedTransactionCommentNumbers"];
        }//$notifiedProperties

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
        $postedUserSelectedItemTransactionCommentDTOs = null;
        $selectedFavoritedUsers = null;
        $selectedCommentDescriptions = null;
        $selectedCategories = null;
        $selectedCondition = null;
        $purchasedUserItemId = null;
        $selectedItemIsBuyerCompleted = false;
        $selectedItemIsSellerCompleted = false;

        $postedUserMaxRatingNumber = 0;
        $postedUserTotalRatingValue = 0;
        $postedUserRoundedRatingValue = 0;
        if($postedUser){
            $postedUserMaxRatingNumber = Rating::where('to_user_id', $postedUserId)->count();
            $postedUserTotalRatingValue = Rating::where('to_user_id', $postedUserId)->sum('rating_value');
            if($postedUserMaxRatingNumber <= 0){
                $postedUserRoundedRatingValue = 0;
            }else{//$postedUserMaxRatingNumber
                $postedUserRoundedRatingValue = round(((double)$postedUserTotalRatingValue / (double)$postedUserMaxRatingNumber));
            }//$postedUserMaxRatingNumber
        }

        if($selectedItem){
            $selectedItemId = $selectedItem->id;
            $selectedItemRatingDTO = RatingService::getRatingDTOFromItemIdAndUserId(
                $selectedItemId,
                $postedUserId
            );

            $draftTransactionComment = TransactionCommentService::getDraftTransactionCommentFromItemIdAndUserId(
                $selectedItemId,
                $postedUserId
            );

            $publishedTransactionComments = TransactionCommentService::getPublishedTransactionCommentsFromItemIdAndUserId(
                $selectedItemId,
                $postedUserId
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

            if($postedUserId === $selectedItemBuyerId){
                $counterpartUserId = $selectedItemSellerId;
            }else if($postedUserId === $selectedItemSellerId){
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
            }//$authUser

            $purchasedUserItemId = TransactionCommentService::getPurchasedUserItemIdFromItemIdAndUserId(
                $itemId,
                $postedUserId
            );

            $purchasedUserItem = UserItem::find($purchasedUserItemId);

            if($purchasedUserItem){
                $selectedItemIsBuyerCompleted = $purchasedUserItem->is_buyer_completed;
                $selectedItemIsSellerCompleted = $purchasedUserItem->is_seller_completed;
            }//$purchasedUserItem

            $transactionCommentDTOService = new TransactionCommentDTOService();

            $postedUserSelectedItemTransactionCommentDTOs 
                = $transactionCommentDTOService->getCurrentTransactionCommentDTOsFromItemIdAndUserId(
                $selectedItemId,
                $postedUserId
            );

            $selectedFavoritedUsers = $selectedItem->favoritedByUsers;
            $selectedCommentDescriptions = $selectedItem->comments->pluck('description')->toArray();
            $selectedCategories = Category::whereIn('id', $selectedCategoryIds)->get();
            $selectedCondition = Condition::findOrFail($selectedConditionId);
            $selectedItemHasBuyerRated = $selectedItem->hasBuyerRated();

        }

        $userDTOService = new UserDTOService();
        $postedUserDTO = $userDTOService->getUserDTOFromUserId($postedUserId);
        $counterpartUserDTO = $userDTOService->getUserDTOFromUserId($counterpartUserId);

        
        if($selectedUserIds){
            if (in_array($authUserId, $selectedUserIds)) {
                $authUserIdCoincidence = true;
            }
        }//$selectedUserIds

        //dd($maxWatchedCounterPartTransactionCommentNumbers);

        return view($returnedViewFile,compact(
            "csrfToken",
            "ratingModalOpenButtonId",
            "ratingModalId",

            "editModalId",
            "editModalClass",
            "editModalContentClass",
            "openEditButtonClass",
            "closeEditModalButtonId",
            "editModalCommentId",
            "editModalMessageId",
            "prefixPublishedTransactionCommentId",

            'dataFieldArgument',
            'dataIdArgument',

            'transactionCellContainerId',
            'transactionCellContainerClass',
            'transactionCellId',
            'transactionCellClass',
            'transactionErrorMessageId',
            'transactionErrorMessageClass',
            'transactionCommentCellIdPrefix',
            'transactionCommentCellClass',
            'transactionCommentPreviewContainerIdPrefix',
            'transactionCommentPreviewContainerClass',
            'transactionCommentPreviewIdPrefix',
            'transactionCommentPreviewClass',
            'transactionCommentEditButtonContainerIdPrefix',
            'transactionCommentEditButtonContainerClass',
            'transactionCommentEditButtonIdPrefix',
            'transactionCommentEditButtonClass',
            'transactionCommentDeleteButtonIdPrefix',
            'transactionCommentDeleteButtonClass',
            'transactionCommentUploadInputIdPrefix',
            'transactionCommentUploadInputClass',
            'transactionCommentUploadLabelIdPrefix',
            'transactionCommentUploadLabelClass',
            'transactionCommentSendButtonIdPrefix',
            'transactionCommentSendButtonClass',
            'transactionCommentErrorMessageIdPrefix',
            'transactionCommentErrorMessageNamePrefix',
            'transactionCommentErrorMessageClass',
            'transactionCommentUserImagePreviewIdPrefix',
            'transactionCommentUserImagePreviewClass',
            'transactionCommentCommentTextareaContainerIdPrefix',
            'transactionCommentCommentTextareaContainerClass',
            'transactionCommentCommentTextareaIdPrefix',
            'transactionCommentCommentTextareaClass',
            'transactionCommentCommentTextareaNamePrefix',
            'transactionImageCellIdPrefix',
            'transactionImageCellClass',
            'transactionImageRemoveButtonIdPrefix',
            'transactionImageRemoveButtonClass',
            'transactionImagePreviewContainerIdPrefix',
            'transactionImagePreviewContainerClass',
            'transactionImagePreviewIdPrefix',
            'transactionImagePreviewClass',
            'transactionImageImageDivIdPrefix',
            'transactionImageImageDivClass',
            'transactionImageErrorMessageIdPrefix',

            'userImageCellIdPrefix',
            'userImageCellClass',
            'userImagePreviewContainerIdPrefix',
            'userImagePreviewContainerClass',
            'userImagePreviewIdPrefix',
            'userImagePreviewClass',
            'userImageImageDivIdPrefix',
            'userImageImageDivClass',
            'userNameDivIdPrefix',
            'userNameDivClass',

            'itemImageDirectory',
            'itemImagePrefix',
			'userImageDirectory',
            'userImagePrefix',
            'coachtechImageDirectory',
            'categoryButtonAppendingClass',
            'routePurchaseUpdateMethodItemId',
            'routeLogin',
            'routeItemDealItemId',
            'routeTransactionSend',
            'customSelectId',
            'previewPostTypes',
            'userKinds',
            'transactionCommentStatuses',
            'phpIniArgumentNames',
            'phpIniSettingSizesInBytes',

            'showFunctionKind',
            'isMultipleFunctionHeader',
            'defaultProfilePreviewUrl',
            'trashPreviewUrl',
            'authUser',
            'authUserId',
            'authUserIdCoincidence',
            'authUserImageName',
            'isFilledWithProfile',
            'itemId',
            'categories',
            'conditions',
            'purchaseMethods',
            'shownItems',
            'tradingItems',
            'totalNotifiedTransactionCommentNumber',
            'maxNotifiedTransactionCommentNumbers',
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
            'counterpartUserDTO',
            'selectedItemCommentNumber',
            'selectedItemHasBuyerRated',
            'selectedCategoryIds',
            'selectedConditionId',
            'selectedItemIsBuyerCompleted',
            'selectedItemIsSellerCompleted',
            'draftTransactionComment',
            'publishedTransactionComments',
            'isPurchased',
            'isPurchasedBy',
            'isOwner',
            'selectedPurchaseMethodId',
            'postedUserMaxRatingNumber',
            'postedUserRoundedRatingValue',
            'postedUserId',
            'postedUserDTO',
            'postedUserSelectedItemTransactionCommentDTOs',
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
