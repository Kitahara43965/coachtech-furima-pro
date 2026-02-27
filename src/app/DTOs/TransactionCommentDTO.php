<?php

namespace App\DTOs;

use Carbon\Carbon;

class TransactionCommentDTO {
    public ?int $transaction_comment_id = null;
    public ?int $user_item_id = null;
    public ?int $user_id = null;
    public ?string $comment = null;
    public ?string $status = null;
    public ?bool $is_watched = null;
    public ?Carbon $created_at = null;
    public ?Carbon $updated_at = null;
    public ?string $user_name = null;
    public ?string $user_username = null;
    public ?array $transaction_image_dtos = null;
}