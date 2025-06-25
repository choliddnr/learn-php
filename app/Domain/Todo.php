<?php

namespace App\Domain;

class Todo
{
    public int $id;
    public string $title;
    public string $description;
    public int $deadline;
    public array $tags;
    public int $user_id;
    public string $status;
    public string $created_at;
    public string $updated_at;
}
