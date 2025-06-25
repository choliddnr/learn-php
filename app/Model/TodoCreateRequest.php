<?php

namespace App\Model;

class TodoCreateRequest
{
    public string $title;
    public ?string $description = "";
    public string $deadline;
    public array $tags;

    public function __construct(string $title, ?string $description, string $deadline, array $tags)
    {
        $this->title = $title;
        $this->description = $description;
        $this->deadline = $deadline;
        $this->tags = $tags;
    }
}
