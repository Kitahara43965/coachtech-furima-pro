@extends('layouts.app')


@section('css')
    <link rel="stylesheet" href="{{ asset('css/deal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/rating.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/preview.css') }}">
@endsection

<div class="deal-board">
    @section('content')
        @php
            $stringOpenRatingModalDisabled = null;
            if($authUserId === $selectedItemSellerId){
                if($selectedItemHasBuyerRated === true){
                    $stringOpenRatingModalDisabled = "";
                }else{//$selectedItemHasBuyerRated
                    $stringOpenRatingModalDisabled = " disabled";
                }//$selectedItemHasBuyerRated
            }else{//$authUserId
                $stringOpenRatingModalDisabled = "";
            }//$authUserId

            $draftTransactionCommentComment = $draftTransactionComment?->comment;

            $counterpartUserImageName = $counterpartUser->image;
            $counterpartUserName = $counterpartUser->name;
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

    <h1>取引画面</h1>

    <div class="deal-title-user-image-and-name">
        <div class="deal-title-user-image-container">
            <img id="preview"
                src="{{$newPreviewUrl ?? $defaultProfilePreviewUrl}}"
                class="deal-title-user-image">
        </div>
        <h1 class="deal-title-user-name">「{{$counterpartUserName}}」さんとの取引画面</h1>
    </div>

    <button id="{{$openRatingModalButtonId}}" {{$stringOpenRatingModalDisabled}}>取引を完了する</button>

    <div class="deal-title-user-image-and-name">
        <div class="deal-item-image-container">
            <img id="preview"
                src="{{$newItemPreviewUrl ?? ''}}"
                class="deal-title-user-image">
        </div>
        <div>
            <div class="deal-title-user-name">{{$itemName}}</div>
            <div class="deal-title-user-name">¥{{$itemPrice}}</div>
        </div>
    </div>

    <form method="POST" action="{{ route('ratingStore.itemId',['item_id'=>$selectedItemId]) }}">
        @csrf
    <!-- モーダル -->
        <div id="{{$ratingModalId}}" class="{{$ratingModalClass}}" style="display:none;">
            <div class="{{$ratingModalContentClass}}">
                <h2>取引が完了しました。</h2>
                <div>今回の取引相手はどうでしたか?</div>

                <div class="rating">
                    @for ($i = 5; $i >= 1; $i--)
                        <input 
                            type="radio" 
                            id="star{{ $i }}" 
                            name="rating_value" 
                            value="{{ $i }}"
                            {{ $selectedItemRatingRatingValue == $i ? 'checked' : '' }}
                        >
                        <label for="star{{ $i }}">★</label>
                    @endfor
                </div>
                <div>
                    <button type="submit">送信する</button>
                </div>
            </div>
        </div>

        <div class="form__error">
            @error('rating_value')
                {{ $message }}
            @enderror
        </div>
    </form>


    <form method="POST" action="{{ route('transactionCommentSend.itemId',['item_id'=>$selectedItemId]) }}">
        @csrf

        @if($publishedTransactionComments)
            @foreach($publishedTransactionComments as $publishedTransactionComment)
                @php
                    $publishedTransactionCommentId = $publishedTransactionComment?->id;
                    $publishedTransactionCommentComment = $publishedTransactionComment?->comment;
                    $publishedTransactionCommentUser = $publishedTransactionComment?->user;
                    $publishedTransactionCommentUserId = $publishedTransactionCommentUser?->id;
                    $publishedTransactionCommentUserImageName = $publishedTransactionCommentUser->image;
                    $publishedTransactionCommentUserName = $publishedTransactionCommentUser->name;
                    $newImageName = null;
                    $newPreviewUrl = null;
                    if($publishedTransactionCommentUserImageName){
                       $newImageName = $publishedTransactionCommentUserImageName;
                       $newPreviewUrl = asset('storage/'.$userImageDirectory.'/'.$newImageName);
                    }//$publishedTransactionCommentUserImageName

                @endphp
                @if($publishedTransactionCommentUserId === $authUserId)
                    <div class = "deal-right-published-textarea-container">
                @else
                    <div class = "deal-left-published-textarea-container">
                @endif
                    <div>
                        @if($publishedTransactionCommentUserId === $authUserId)
                            <div class="deal-left-user-image-and-name">
                        @else
                            <div class="deal-right-user-image-and-name">
                                <div class="deal-user-name">{{$publishedTransactionCommentUserName}}</div>
                        @endif
                                <div class="deal-user-image-container">
                                    <img id="preview"
                                        src="{{$newPreviewUrl ?? $defaultProfilePreviewUrl}}"
                                        class="deal-user-image">
                                </div>
                        @if($publishedTransactionCommentUserId === $authUserId)
                                <div class="deal-user-name">{{$publishedTransactionCommentUserName}}</div>
                            </div>
                        @else
                            </div>
                        @endif

                        <textarea id="{{$prefixPublishedTransactionCommentId.$publishedTransactionCommentId}}"
                            class = "deal-published-textarea"
                            disabled>{{$publishedTransactionCommentComment}}</textarea>
                    </div>
                </div>

                
                <!--
                    <div>
                        <button type="button" 
                                class="{{$openEditButtonClass}}"
                                data-transaction-id="{{$publishedTransactionCommentId}}"
                                data-message="{{$publishedTransactionCommentComment}}"
                        >編集</button>
                        <button type="button">削除</button>
                    </div>
                -->
            @endforeach
        @endif

        <div class="deal-comment-container">
                <textarea 
                    name="{{$transactionCommentName}}" 
                    placeholder="取引メッセージを入力してください"
                    class="deal-draft-textarea"
                >{{$draftTransactionCommentComment}}</textarea>

                <button type="submit" class="deal-comment-button">コメント</button>
                <div id="{{$previewGridId}}" class="{{$previewGridClass}}"></div>
        </div>
        <div class="deal-comment-container">
            <div class="form__error">
                @error($transactionCommentName)
                    {{ $message }}
                @enderror
            </div>
        </div>
         

    </form>
</div>

<!-- 編集モーダル -->
<!-- <div id="{{$editModalId}}" class="fixed inset-0 hidden">
  <div class="modal-content">
    <h2 class="text-xl font-bold mb-4">コメント編集</h2>
    <form id="editForm" action="{{route('commentEditItemId',['item_id'=>$selectedItemId])}}" method="POST">
      @csrf
      <input type="hidden" id="{{$editModalCommentId}}" name="transaction_comment_id">
      <textarea name="message" id="{{$editModalMessageId}}"></textarea>
      <br>
      <button type="submit">保存</button>
    </form>
  </div>
</div> -->

<script>
    window.ratingModalConfig = {
        openRatingModalButtonId: @json($openRatingModalButtonId),
        ratingModalId: @json($ratingModalId),
    };

    window.editModalConfig = {
        editModalId: @json($editModalId),
        openEditButtonClass: @json($openEditButtonClass),
        closeEditModalButtonId: @json($closeEditModalButtonId),
        editModalCommentId:@json($editModalCommentId),
        editModalMessageId:@json($editModalMessageId),
        prefixPublishedTransactionCommentId:@json($prefixPublishedTransactionCommentId),
    };
    
    window.previewConfig = {
        csrfToken:@json($csrfToken),
        previewImageInputId: @json($previewImageInputId),
        previewGridId:@json($previewGridId),
        previewRemoveButtonClass:@json($previewRemoveButtonClass),
        previewCellClass:@json($previewCellClass),
        previewGridClass:@json($previewGridClass),
        previewCommentSendButtonId:@json($previewCommentSendButtonId),
        previewPostTypes:@json($previewPostTypes),
        selectedItemId:@json($selectedItemId),
        routeTransactionCommentUpdateItemId:@json($routeTransactionCommentUpdateItemId),
        transactionCommentName:@json($transactionCommentName),
    }
</script>

<script src="{{ asset('js/rating-modal.js') }}"></script>
<script src="{{ asset('js/transaction-comment/preview.js') }}" type="module"></script>

@endsection

