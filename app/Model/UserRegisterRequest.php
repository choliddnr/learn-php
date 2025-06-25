<?php

namespace App\Model;

class UserRegisterRequest
{
    public string $username;
    public string $email;
    public string $password;
    public string $confirm_password;

    public function __construct(string $username, string $email, string $password, string $confirm_password)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->confirm_password = $confirm_password;
    }
}
