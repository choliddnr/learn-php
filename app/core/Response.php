<?php

namespace App\Core;

abstract class Response
{
    public ?string $message = null;
    public ?array $errors = [];
}
