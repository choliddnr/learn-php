<?php

namespace App\Domain;

class Todo
{
    public string $id;
    public string $title;
    public string $description;
    public int $deadline;
    public int $user_id;
    public string $status;
    public string $created_at;
    public string $updated_at;

    // public function __construct($id, $title, $description, $deadline, $status, $created_at, $updated_at)
    // {
    //     $this->id = $id;
    //     $this->title = $title;
    //     $this->description = $description;
    //     $this->deadline = $deadline;
    //     $this->status = $status;
    //     $this->created_at = $created_at;
    //     $this->updated_at = $updated_at;
    // }
}