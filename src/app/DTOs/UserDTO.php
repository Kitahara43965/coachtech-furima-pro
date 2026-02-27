<?php

namespace App\DTOs;

use Carbon\Carbon;

class UserDTO {
    public ?int $user_id = null;
    public int $login_time_number = 0;
    public ?string $name = null;
    public ?string $email = null;
    public ?Carbon $email_verified_at = null;
    public ?string $password = null;
    public ?string $rememberToken = null;
    public bool $is_filled_with_profile;
    public ?string $username = null;
    public ?string $postcode = null;
    public ?string $address = null;
    public ?string $building = null;
    public ?string $image = null;
    public ?Carbon $created_at = null;
    public ?Carbon $updated_at = null;
    public ?string $image_url = null;
}




