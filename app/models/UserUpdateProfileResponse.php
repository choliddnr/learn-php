<?php

namespace App\Models;

use App\Core\Response;
use App\Domain\User;

class UserUpdateProfileResponse extends Response
{
    public ?User $user = null;

}