<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Constants\PreviewErrorStatus;

class TransactionComment extends Model
{
    use HasFactory;

    protected $table = 'transaction_comments';
    protected $fillable = [
        'user_item_id',
        'user_id',
        'is_watched',
        'status',
        'comment'
    ];

    public function userItem()
    {
        return $this->belongsTo(UserItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionImages()
    {
        return $this->hasMany(TransactionImage::class);
    }

    
}
