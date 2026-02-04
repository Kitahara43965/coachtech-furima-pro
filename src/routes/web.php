<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TransactionCommentController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerifyEmailController;



Route::get('/', [ShowController::class, 'index'])
    ->name('index');
Route::get('/item/{item_id}',[ShowController::class, 'itemItemId'])
    ->name('item.itemId');
Route::get('/register', [ShowController::class, 'register'])
    ->name('register');
Route::post('/register', [RegisterController::class, 'registerStore'])
    ->name('registerStore');
Route::get('/login', [ShowController::class, 'login'])
    ->name('login');
Route::post('/login', [LoginController::class, 'loginStore'])
    ->name('loginStore');

Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [ShowController::class, 'showEmailVerification'])
        ->name('verification.notice');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    Route::post('/verify-email', [VerifyEmailController::class,'verifyEmail'])
        ->name('verification.manual');

    Route::post('/resend-email', [VerifyEmailController::class,'resendEmail'])
        ->name('resendEmail');
});

Route::middleware(['auth', 'signed', 'throttle:6,1'])->group(function () {
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class,'emailVerifyIdHash'])
        ->name('verification.verify');
});

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/mypage/profile', [ShowController::class, 'mypageProfile'])
        ->name('mypage.profile');
    Route::post('/mypage/profile/update', [ProfileController::class, 'mypageProfileUpdate'])
        ->name('mypage.profile.update');
    Route::get('/profile/count-images', [ProfileController::class, 'countImages'])
        ->name('profile.image.count');
});

Route::middleware(['auth','verified','profile.complete'])->group(function () {
    Route::get('/mypage', [ShowController::class, 'mypage'])
        ->name('mypage');
    Route::get('/sell', [ShowController::class, 'sell'])
        ->name('sell');
    Route::get('/sell/count-images', [SellController::class, 'countImages'])
        ->name('sell.image.count');
    Route::post('/sell/store', [SellController::class, 'sellStore'])
        ->name('sell.store');
    Route::post('/sell/update/{item_id}', [SellController::class, 'sellUpdateItemId'])
        ->name('sell.update.itemId');
    Route::delete('/sell/delete/{item_id}', [SellController::class, 'sellDeleteItemId'])
        ->name('sell.delete.itemId');
    Route::get('/purchase/{item_id}', [ShowController::class, 'purchaseItemId'])
        ->name('purchase.itemId');
    Route::post('/purchase/updateMethod/{item_id}', [PurchaseController::class, 'purchaseUpdateMethodItemId'])
        ->name('purchase.updateMethod.itemId');
    Route::get('/purchase/address/{item_id}', [ShowController::class, 'purchaseAddressItemId'])
        ->name('purchase.address.itemId');
    Route::post('/purchase/address/update/{item_id}', [PurchaseController::class, 'purchaseAddressUpdateItemId'])
        ->name('purchase.address.update.itemId');
    Route::get('/item/edit/{item_id}', [ShowController::class, 'itemEditItemId'])
        ->name('item.edit.itemId');
    Route::post('/item/favorite/{item_id}', [FavoriteController::class, 'itemFavoriteItemId'])
        ->name('item.favorite.itemId');
    Route::post('/item/comments/{item_id}', [CommentController::class, 'itemCommentsItemId'])
        ->name('item.comments.itemId');
    Route::post('/purchase/store/{item_id}', [PurchaseController::class, 'purchaseStoreItemId'])
        ->name('purchase.store.itemId');

    Route::get('/item/deal/{item_id}', [ShowController::class, 'itemDealItemId'])
        ->name('item.deal.itemId');

    Route::post('/rating-store/{item_id}',[RatingController::class, 'ratingStoreItemId'])
        ->name('ratingStore.itemId');

    Route::post('/transaction-comment-update/{item_id}',[TransactionCommentController::class, 'transactionCommentUpdateItemId'])
        ->name('transactionCommentUpdate.itemId');
    
    Route::post('/transaction-comment-send/{item_id}',[TransactionCommentController::class, 'transactionCommentSendItemId'])
        ->name('transactionCommentSend.itemId');

    Route::post('/comment-edit/{item_id}',[TransactionCommentController::class, 'commentEditItemId'])
        ->name('commentEditItemId');

});
