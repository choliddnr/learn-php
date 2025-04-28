<?php

namespace App\Model;

use App\Core\Response;
use App\Domain\Todo;

class TodoUpdateResponse extends Response
{
    public ?Todo $todo;

}