@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/rating.css') }}">
@endsection

@php
    use App\Constants\ShowFunctionKinds\ShowFunctionKind;
    
    $homeIndexBladeActionKind = 1;
    $mypageIndexBladeActionKind = 2;

    $evaluationHrefShownItemKind = 1;
    $dealHrefShownItemKind = 2;

    if($showFunctionKind == ShowFunctionKind::INDEX_INDEX){
        $indexBladeActionKind = $homeIndexBladeActionKind;
        $hrefShownItemKind = $evaluationHrefShownItemKind;
        $homeIndexToggleClass = "index-toggle-red-text";
        $mylistIndexToggleClass = "index-toggle-black-text";
        $boughtGoodsIndexToggleClass = "index-toggle-black-text";
        $soldGoodsIndexToggleClass = "index-toggle-black-text";
        $dealGoodsIndexToggleClass = "index-toggle-black-text";
    }else if($showFunctionKind == ShowFunctionKind::MY_LIST_INDEX){
        $indexBladeActionKind = $homeIndexBladeActionKind;
        $hrefShownItemKind = $evaluationHrefShownItemKind;
        $homeIndexToggleClass = "index-toggle-black-text";
        $mylistIndexToggleClass = "index-toggle-red-text";
        $boughtGoodsIndexToggleClass = "index-toggle-black-text";
        $soldGoodsIndexToggleClass = "index-toggle-black-text";
        $dealGoodsIndexToggleClass = "index-toggle-black-text";
    }else if($showFunctionKind == ShowFunctionKind::SOLD_GOODS_MYPAGE){
        $indexBladeActionKind = $mypageIndexBladeActionKind;
        $hrefShownItemKind = $evaluationHrefShownItemKind;
        $homeIndexToggleClass = "index-toggle-black-text";
        $mylistIndexToggleClass = "index-toggle-black-text";
        $boughtGoodsIndexToggleClass = "index-toggle-black-text";
        $soldGoodsIndexToggleClass = "index-toggle-red-text";
        $dealGoodsIndexToggleClass = "index-toggle-black-text";
    }else if($showFunctionKind == ShowFunctionKind::BOUGHT_GOODS_MYPAGE){
        $indexBladeActionKind = $mypageIndexBladeActionKind;
        $hrefShownItemKind = $evaluationHrefShownItemKind;
        $homeIndexToggleClass = "index-toggle-black-text";
        $mylistIndexToggleClass = "index-toggle-black-text";
        $boughtGoodsIndexToggleClass = "index-toggle-red-text";
        $soldGoodsIndexToggleClass = "index-toggle-black-text";
        $dealGoodsIndexToggleClass = "index-toggle-black-text";
    }else if($showFunctionKind == ShowFunctionKind::DEAL_GOODS_MYPAGE){
        $indexBladeActionKind = $mypageIndexBladeActionKind;
        $hrefShownItemKind = $dealHrefShownItemKind;
        $homeIndexToggleClass = "index-toggle-black-text";
        $mylistIndexToggleClass = "index-toggle-black-text";
        $boughtGoodsIndexToggleClass = "index-toggle-black-text";
        $soldGoodsIndexToggleClass = "index-toggle-black-text";
        $dealGoodsIndexToggleClass = "index-toggle-red-text";
    }else{//$showFunctionKind
        $indexBladeActionKind = $homeIndexBladeActionKind;
        $hrefShownItemKind = $evaluationHrefShownItemKind;
        $homeIndexToggleClass = "index-toggle-black-text";
        $mylistIndexToggleClass = "index-toggle-black-text";
        $boughtGoodsIndexToggleClass = "index-toggle-black-text";
        $soldGoodsIndexToggleClass = "index-toggle-black-text";
        $dealGoodsIndexToggleClass = "index-toggle-black-text";
    }//$showFunctionKind

@endphp


@section('content')

@php
    if($authUserImageName){
        $newImageName = $authUserImageName;
        $newPreviewUrl = asset('storage/'.$userImageDirectory.'/'.$newImageName);
    }else{//$authUserImageName
        $newImageName = null;
        $newPreviewUrl = null;
    }//$authUserImageName
@endphp

    <div class="index-board">
        
        @if($indexBladeActionKind == $homeIndexBladeActionKind)
            <div class="index-toggle-upper-blank">
            <div class="index-toggle-block">
                <div class="index-toggle-inner-block">
                    <a class="{{$homeIndexToggleClass}}" href="{{ route('index') }}">
                        おすすめ
                    </a>
                    <a class="{{$mylistIndexToggleClass}}" href="{{ route('index', ['tab' => 'mylist']) }}">
                        マイリスト
                    </a>
                </div>
            </div>
        @elseif($indexBladeActionKind == $mypageIndexBladeActionKind)
                
            <div class="index-user-image-block-upper-blank">
            <div class="index-user-image-block">
                <div class="index-user-image-block-left">
                    <div class="user-image-container">
                        <img id="preview"
                            src="{{$newPreviewUrl ?? $defaultProfilePreviewUrl}}"
                            class="user-image">
                    </div>
                    <div>
                        <div class="index-user-name">
                            {{$authUser?->username}}
                        </div>
                        @if($authUserMaxRatingNumber >= 1)
                            <div class="rating disabled">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input 
                                        type="radio" 
                                        id="star{{ $i }}" 
                                        name="rating_value" 
                                        value="{{ $i }}"
                                        {{ $authUserRoundedRatingValue == $i ? 'checked' : '' }}
                                    >
                                    <label for="star{{ $i }}">★</label>
                                @endfor
                            </div>
                        @endif
                    </div>
                </div>
                <a class="index-profile-button" href="{{ route('mypage.profile') }}">
                    プロフィールを編集
                </a>
            </div>
            <div class="index-toggle-upper-blank">
            <div class="index-toggle-block">
                <div class="index-toggle-inner-block">
                    <a class="{{$soldGoodsIndexToggleClass}}" href="{{ route('mypage', ['page' => 'sell']) }}">
                        出品した商品
                    </a>
                    <a class="{{$boughtGoodsIndexToggleClass}}" href="{{ route('mypage', ['page' => 'buy']) }}">
                        購入した商品
                    </a>
                    <a class="{{$dealGoodsIndexToggleClass}}" href="{{ route('mypage', ['page' => 'deal']) }}">
                        取引中の商品
                    </a>
                </div>
            </div>
        @endif

        <div class="index-section-borderline"></div>


        <div class="index-item-card-container">
            @if($shownItems)
                @foreach($shownItems as $shownItem)
                    @php
                        $candidateNewImageName = $shownItem->image;
                        if($candidateNewImageName){
                            $newImageName = $candidateNewImageName;
                            if($shownItem->is_default){
                                $newPreviewUrl = asset('storage/'.$coachtechImageDirectory.'/'.$newImageName);
                            }else{
                                $newPreviewUrl = asset('storage/'.$itemImageDirectory.'/'.$newImageName);
                            }
                        }else{//$candidateNewImageName
                            $newPreviewUrl = null;
                            $newImageName = null;
                        }//$candidateNewImageName

                        $isPurchased = $shownItem->isPurchased();

                    @endphp

                        @if($hrefShownItemKind === $evaluationHrefShownItemKind)
                            <a href="{{ route('item.itemId', ['item_id' => $shownItem->id]) }}" class="index-item-card">
                        @elseif($hrefShownItemKind === $dealHrefShownItemKind)
                            <a href="{{ route('item.deal.itemId', ['item_id' => $shownItem->id]) }}" class="index-item-card">
                        @else
                            <a href="{{ route('item.itemId', ['item_id' => $shownItem->id]) }}" class="index-item-card">
                        @endif

                    
                        <div class="index-image-container">
                            <div class="index-item-image-container ">
                                <img src="{{ $newPreviewUrl }}" class="index-item-image">
                            </div>
                            @if($isPurchased)
                                <div class="index-sold-text">Sold</div>
                            @endif
                        </div>
                        <div class="index-item-card-footer">
                            <span class="index-item-name">{{ $shownItem->name }}</span>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </div>

@endsection