<?php

namespace App\DTOs;

use Carbon\Carbon;

class TransactionImageDTO {
    public ?int $transaction_image_id = null;
    public ?int $transaction_comment_id = null;
    public ?string $image = null;
    public ?string $image_url = null;
    public ?Carbon $created_at = null;
    public ?Carbon $updated_at = null;
}