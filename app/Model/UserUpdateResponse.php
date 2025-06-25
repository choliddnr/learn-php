<?php

namespace App\Model;

use App\Core\Response;
use App\Domain\User;
use App\Domain\UserProfile;

class UserUpdateResponse extends Response
{
    public ?User $user = null;
    public ?UserProfile $profile = null;
}
