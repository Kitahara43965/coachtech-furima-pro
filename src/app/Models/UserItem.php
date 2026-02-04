<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;
use App\Constants\UserItemStatus;

class UserItem extends Pivot
{
    protected $table = 'user_item';

    public function transactionComments()
    {
        return $this->hasMany(TransactionComment::class, 'user_item_id'); // リレーションのキーを適切に設定
    }

    
}

