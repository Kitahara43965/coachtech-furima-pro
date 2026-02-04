<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Constants\UserItemStatus;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name','email','password','username','is_filled_with_profile','postcode','address','building','image'
    ];

    protected $hidden = ['password','remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_filled_with_profile' => 'boolean',
    ];

    // 出品/所有商品
    public function ownedItems()
    {
        return $this->belongsToMany(Item::class, 'user_item')
                    ->wherePivot('type', 'ownership')
                    ->withTimestamps();
    }

    // お気に入り商品
    public function favoriteItems()
    {
        return $this->belongsToMany(Item::class, 'user_item')
                    ->wherePivot('type', 'favorite')
                    ->withTimestamps();
    }

    // 購入商品
    public function pendingItems(){
        return $this->belongsToMany(Item::class, 'user_item')
                ->wherePivot('type', 'purchase')
                ->withPivot([
                    'type',
                    'status',
                    'is_buyer_completed',
                    'is_seller_completed',
                    'completed_at',
                    'purchase_quantity',
                    'price_at_purchase',
                    'purchased_at',
                    'purchase_method_id',
                    'is_filled_with_delivery_address',
                    'delivery_postcode',
                    'delivery_address',
                    'delivery_building',
                ])
                ->withTimestamps();
    }

    public function purchasedAndCompletedItems()
    {
        return $this->pendingItems()
            ->wherePivot('status', UserItemStatus::COMPLETED)
            ->whereNotNull('user_item.completed_at')
            ->wherePivot('purchase_quantity', '>=', 1)
            ->withTimestamps();
    }

    public function tradingItems()
    {
        $tradingItemsQuery = Item::where(function ($query) {
                $query->whereHas('purchasedByUsers', function ($q) {
                        $q->where('users.id', $this->id)
                        ->where('user_item.type', 'purchase')
                        ->where('user_item.status', UserItemStatus::TRADING)
                        ->where('user_item.purchase_quantity', '>=', 1);
                    })
                    ->orWhereHas('usersByOwnership', function ($q) {
                        $q->where('users.id', $this->id);
                    });
            })
            ->whereHas('purchasedByUsers', function ($q) {
                $q->where('user_item.type', 'purchase')
                ->where('user_item.status', UserItemStatus::TRADING)
                ->where('user_item.purchase_quantity', '>=', 1);
            });
        
        return($tradingItemsQuery);
    }

    public function givenRatings()
    {
        return $this->hasMany(Rating::class, 'from_user_id');
    }

    // このユーザーが受けた評価
    public function receivedRatings()
    {
        return $this->hasMany(Rating::class, 'to_user_id');
    }

    // 購入者が出品者へ出した評価
    public function ratingsToSeller()
    {
        return $this->givenRatings()->where('type', 'buyer_to_seller');
    }

    // 出品者が購入者へ出した評価
    public function ratingsToBuyer()
    {
        return $this->givenRatings()->where('type', 'seller_to_buyer');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'user_item')
            ->withPivot('type', 'purchase_quantity', 'price_at_purchase', 'purchased_at', 'status')
            ->withTimestamps();
    }
}