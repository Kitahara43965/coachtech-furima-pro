@extends('layouts.app')


@section('css')
    <link rel="stylesheet" href="{{ asset('css/deal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/rating.css') }}">
    <link rel="stylesheet" href="{{ asset('css/rating-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/transaction.css') }}">
@endsection


@section('content')

    <div class="deal-board">
        <div class="deal-other-items">
            <h2 class="deal-other-items-title">その他の取引</h2>
            @if($tradingItems)
                @foreach($tradingItems as $index => $tradingItem)
                    @php
                        $maxNotifiedTransactionCommentNumber = $maxNotifiedTransactionCommentNumbers[$index];
                    @endphp
                    @if($tradingItem&&$selectedItem)
                        @if($tradingItem->id !== $selectedItem->id)
                            <div class="deal-other-items-button-container">
                                @if($maxNotifiedTransactionCommentNumber >= 1)
                                    <div class="deal-other-items-notified-transaction-comment-number">
                                        {{$maxNotifiedTransactionCommentNumber}}
                                    </div>
                                @endif
                                <a href="{{ route('item.deal.itemId', ['item_id' => $tradingItem->id]) }}" class="deal-other-items-button">{{$tradingItem->name}}</a>
                            </div>
                        @endif
                    @endif
                @endforeach
            @endif
        </div>
        <div class="deal-transaction-cell-container">

            @php
                $buttonDisabledMarker = 0;
                $ratingModalOpenButtonClass = "rating-modal-open-button";
                $stringOpenRatingModalDisabled = null;
                if($authUserId === $selectedItemSellerId){
                    $buttonDisabledMarker = 1;
                }//$authUserId

                if($buttonDisabledMarker === 0){
                    $stringOpenRatingModalDisabled = "";
                    $disableAppendingClass = "";
                }else{//$buttonDisabledMarker
                    $stringOpenRatingModalDisabled = " disabled";
                    $disableAppendingClass = " disabled";
                }//$buttonDisabledMarker


                $draftTransactionCommentComment = $draftTransactionComment?->comment;

                $counterpartUserImageName = $counterpartUser->image;
                $counterpartUserUserName = $counterpartUser->username;
                $newImageName = null;
                $newPreviewUrl = null;
                if($counterpartUserImageName){
                    $newImageName = $counterpartUserImageName;
                    $newPreviewUrl = asset('storage/'.$userImageDirectory.'/'.$newImageName);
                }//$counterpartUserImageName

                $itemImageName = $selectedItem->image;
                $itemName = $selectedItem->name;
                $itemPrice = $selectedItem->price;
                $newItemImageName = null;
                $newItemPreviewUrl = null;
                if($itemImageName){
                    $newItemImageName = $itemImageName;
                    if($selectedItem->is_default){
                        $newItemPreviewUrl = asset('storage/'.$coachtechImageDirectory.'/'.$newItemImageName);
                    }else{
                        $newItemPreviewUrl = asset('storage/'.$itemImageDirectory.'/'.$newItemImageName);
                    }
                }//$itemImageName

            @endphp

            <div class="user-image-and-rating-modal-open-button-container">
                <div class="deal-title-user-image-and-name">
                    <div class="deal-title-user-image-container">
                        <img id="preview"
                            src="{{$newPreviewUrl ?? $defaultProfilePreviewUrl}}"
                            class="deal-title-user-image">
                    </div>
                    <h2 class="deal-title-user-name">「{{$counterpartUserUserName}}」さんとの取引画面</h2>
                </div>

                <button id="{{$ratingModalOpenButtonId}}" 
                        class="{{$ratingModalOpenButtonClass.$disableAppendingClass}}" 
                        {{$stringOpenRatingModalDisabled}}>取引を完了する</button>
            </div>

            <form method="POST" action="{{ route('ratingStore.itemId',['item_id'=>$selectedItemId]) }}">
                @csrf
            <!-- モーダル -->
                <div id="{{$ratingModalId}}" class="rating-modal" style="display:none;">
                    <div class="rating-modal-content">
                        <div class="rating-modal-title">取引が完了しました。</div>
                        <div class="rating-modal-horizontal-line"></div>
                        <div class="rating-modal-question">今回の取引相手はどうでしたか?</div>
                        <div class="rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input 
                                    type="radio" 
                                    id="star{{ $i }}" 
                                    name="rating_value" 
                                    value="{{ $i }}"
                                    {{ $selectedItemRatingRatingValue == $i ? 'checked' : '' }}
                                >
                                <label for="star{{ $i }}" class="rating-star">★</label>
                            @endfor
                        </div>
                        <div class="rating-modal-horizontal-line"></div>
                        <div>
                            <div class="rating-modal-submit-button-container">
                                <button type="submit" class="rating-modal-submit-button">送信する</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form__error">
                    @error('rating_value')
                        {{ $message }}
                    @enderror
                </div>
            </form>

            <div class="deal-horizontal-line"></div>

            <div class="deal-title-item-image">
                <div class="deal-item-image-container">
                    <img id="preview"
                        src="{{$newItemPreviewUrl ?? ''}}"
                        class="deal-title-user-image">
                </div>
                <div>
                    <h1 class="deal-title-item-name">{{$itemName}}</h1>
                    <div class="deal-title-item-price">¥{{$itemPrice}}</div>
                </div>
            </div>

            <div class="deal-horizontal-line"></div>

            

            <div id="{{$transactionCellContainerId}}" class="{{$transactionCellContainerClass}}"></div>
        </div>
    </div>

<script>
    
    window.previewConfig = {
        phpIniArgumentNames:@json($phpIniArgumentNames),
        phpIniSettingSizesInBytes:@json($phpIniSettingSizesInBytes),
        previewPostTypes:@json($previewPostTypes),
        userKinds:@json($userKinds),
        transactionCommentStatuses:@json($transactionCommentStatuses),
        csrfToken:@json($csrfToken),
        dataFieldArgument:@json($dataFieldArgument),
        dataIdArgument:@json($dataIdArgument),
        routeLogin:@json($routeLogin),
        routeItemDealItemId:@json($routeItemDealItemId),
        routeTransactionSend:@json($routeTransactionSend),
        selectedItemId:@json($selectedItemId),
        postedUserDTO:@json($postedUserDTO),
        counterpartUserDTO:@json($counterpartUserDTO),
        selectedItemSellerId:@json($selectedItemSellerId),
        selectedItemBuyerId:@json($selectedItemBuyerId),
        transactionCellContainerId:@json($transactionCellContainerId),
        transactionCellContainerClass:@json($transactionCellContainerClass),
        transactionCellId:@json($transactionCellId),
        transactionCellClass:@json($transactionCellClass),
        transactionErrorMessageId:@json($transactionErrorMessageId),
        transactionErrorMessageClass:@json($transactionErrorMessageClass),
        transactionCommentCellIdPrefix:@json($transactionCommentCellIdPrefix),
        transactionCommentCellClass:@json($transactionCommentCellClass),
        transactionCommentPreviewContainerIdPrefix:@json($transactionCommentPreviewContainerIdPrefix),
        transactionCommentPreviewContainerClass:@json($transactionCommentPreviewContainerClass),
        transactionCommentPreviewIdPrefix:@json($transactionCommentPreviewIdPrefix),
        transactionCommentPreviewClass:@json($transactionCommentPreviewClass),
        transactionCommentEditButtonContainerIdPrefix:@json($transactionCommentEditButtonContainerIdPrefix),
        transactionCommentEditButtonContainerClass:@json($transactionCommentEditButtonContainerClass),
        transactionCommentEditButtonIdPrefix:@json($transactionCommentEditButtonIdPrefix),
        transactionCommentEditButtonClass:@json($transactionCommentEditButtonClass),
        transactionCommentDeleteButtonIdPrefix:@json($transactionCommentDeleteButtonIdPrefix),
        transactionCommentDeleteButtonClass:@json($transactionCommentDeleteButtonClass),
        transactionCommentUploadInputIdPrefix:@json($transactionCommentUploadInputIdPrefix),
        transactionCommentUploadInputClass:@json($transactionCommentUploadInputClass),
        transactionCommentUploadLabelIdPrefix:@json($transactionCommentUploadLabelIdPrefix),
        transactionCommentUploadLabelClass:@json($transactionCommentUploadLabelClass),
        transactionCommentSendButtonIdPrefix:@json($transactionCommentSendButtonIdPrefix),
        transactionCommentSendButtonClass:@json($transactionCommentSendButtonClass),
        transactionCommentErrorMessageIdPrefix:@json($transactionCommentErrorMessageIdPrefix),
        transactionCommentErrorMessageNamePrefix:@json($transactionCommentErrorMessageNamePrefix),
        transactionCommentErrorMessageClass:@json($transactionCommentErrorMessageClass),
        transactionCommentUserImagePreviewIdPrefix:@json($transactionCommentUserImagePreviewIdPrefix),
        transactionCommentUserImagePreviewClass:@json($transactionCommentUserImagePreviewClass),
        transactionCommentCommentTextareaContainerIdPrefix:@json($transactionCommentCommentTextareaContainerIdPrefix),
        transactionCommentCommentTextareaContainerClass:@json($transactionCommentCommentTextareaContainerClass),
        transactionCommentCommentTextareaIdPrefix:@json($transactionCommentCommentTextareaIdPrefix),
        transactionCommentCommentTextareaClass:@json($transactionCommentCommentTextareaClass),
        transactionCommentCommentTextareaNamePrefix:@json($transactionCommentCommentTextareaNamePrefix),
        transactionImageCellIdPrefix:@json($transactionImageCellIdPrefix),
        transactionImageCellClass:@json($transactionImageCellClass),
        transactionImageRemoveButtonIdPrefix:@json($transactionImageRemoveButtonIdPrefix),
        transactionImageRemoveButtonClass:@json($transactionImageRemoveButtonClass),
        transactionImagePreviewContainerIdPrefix:@json($transactionImagePreviewContainerIdPrefix),
        transactionImagePreviewContainerClass:@json($transactionImagePreviewContainerClass),
        transactionImageImageDivIdPrefix:@json($transactionImageImageDivIdPrefix),
        transactionImageImageDivClass:@json($transactionImageImageDivClass),
        transactionImagePreviewIdPrefix:@json($transactionImagePreviewIdPrefix),
        transactionImagePreviewClass:@json($transactionImagePreviewClass),
        transactionImageErrorMessageIdPrefix:@json($transactionImageErrorMessageIdPrefix),
        userImageCellIdPrefix:@json($userImageCellIdPrefix),
        userImageCellClass:@json($userImageCellClass),
        userImagePreviewContainerIdPrefix:@json($userImagePreviewContainerIdPrefix),
        userImagePreviewContainerClass:@json($userImagePreviewContainerClass),
        userImagePreviewIdPrefix:@json($userImagePreviewIdPrefix),
        userImagePreviewClass:@json($userImagePreviewClass),
        userImageImageDivIdPrefix:@json($userImageImageDivIdPrefix),
        userImageImageDivClass:@json($userImageImageDivClass),
        userNameDivIdPrefix:@json($userNameDivIdPrefix),
        userNameDivClass:@json($userNameDivClass),
        ratingModalOpenButtonId: @json($ratingModalOpenButtonId),
        ratingModalId: @json($ratingModalId),
        selectedItemIsBuyerCompleted:@json($selectedItemIsBuyerCompleted),
        selectedItemIsSellerCompleted:@json($selectedItemIsSellerCompleted),
    }
</script>
<script src="{{ asset('js/transaction-comment/preview.js') }}" type="module"></script>

@endsection

