<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionCompletedMail extends Mailable
{
    public $item;
    public $buyer;

    public function __construct($item, $buyer)
    {
        $this->item = $item;
        $this->buyer = $buyer;
    }

    public function build()
    {
        // メールを作成
        return $this->subject('取引完了のお知らせ')
                    ->html('<h1>取引が完了しました</h1>
                           <p>商品名: ' . $this->item->name . '</p>
                           <p>購入者: ' . $this->buyer->name . '</p>
                           <p>ありがとうございました！</p>');
    }
}

