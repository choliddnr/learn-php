<?php

namespace App\Model;

use App\Core\Response;
use App\Domain\UserProfile;

class UserCreateProfileResponse extends Response
{
    public ?UserProfile $profile = null;
}
