<?php

namespace App\Models;

use App\Core\Response;
use App\Domain\User;

class UserRegisterResponse extends Response
{
    public User $user;
}