<?php
namespace App\Models;

class TodoCreateRequest
{
    public string $title;
    public ?string $description = "";
    public string $deadline;

    public function __construct(string $title, ?string $description, string $deadline, )
    {
        $this->title = $title;
        $this->description = $description;
        $this->deadline = $deadline;
    }
}
