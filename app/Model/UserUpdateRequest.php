<?php

namespace App\Model;

use App\Domain\UploadedFile;

class UserUpdateRequest
{
    public ?int $id = null;
    public ?string $username = null;
    public ?string $current_password = null;
    public ?string $new_password = null;
    public ?string $confirm_password = null;
    public ?string $fullname = null;
    public ?string $whatsapp = null;
    public ?string $gender = '0';
    public ?UploadedFile $avatar = null;
}
