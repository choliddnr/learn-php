<?php

namespace App\Model;

use App\Core\Response;
use App\Domain\Todo;

class TodoCreateResponse extends Response
{
    public Todo $todo;
}