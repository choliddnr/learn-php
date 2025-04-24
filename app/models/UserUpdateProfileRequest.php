<?php

namespace App\Models;


class UserUpdateProfileRequest
{
    public ?int $id = null;
    public ?string $name = null;
    public ?string $current_password = null;
    public ?string $new_password = null;
    public ?string $confirm_password = null;
}