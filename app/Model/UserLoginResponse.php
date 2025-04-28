<?php

namespace App\Model;

use App\Core\Response;
use App\Domain\User;

class UserLoginResponse extends Response
{
    public User $user;
}