<?php

namespace App\DTOs;

class RatingDTO{
    public ?int $rating_id = null;
    public bool $is_rating_existence = false;
    public ?int $from_user_id = null;
    public ?int $to_user_id = null;
    public ?int $item_id = null;
    public ?string $type = null;
}//Message