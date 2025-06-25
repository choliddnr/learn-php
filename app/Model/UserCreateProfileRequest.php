<?php

namespace App\Model;

use App\Domain\UploadedFile;


class UserCreateProfileRequest
{
    public ?string $fullname = null;
    public ?string $whatsapp = null;
    public ?string $gender = null;
    public ?UploadedFile $avatar = null;
}
