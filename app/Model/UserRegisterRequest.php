<?php

namespace App\Model;

class UserRegisterRequest
{
    public string $name;
    public string $email;
    public string $password;
    public string $confirm_password;

    public function __construct(string $name, string $email, string $password, string $confirm_password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->confirm_password = $confirm_password;
    }
}