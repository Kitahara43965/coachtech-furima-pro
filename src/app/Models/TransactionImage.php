<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionImage extends Model
{
    use HasFactory;

    protected $fillable = ['image'];

    public function transactionComment()
    {
        return $this->belongsTo(TransactionComment::class, 'transaction_comment_id');
    }
}