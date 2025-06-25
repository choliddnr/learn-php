<?php

namespace App\Model;

class TodoUpdateRequest
{
    public ?int $id = null;
    public ?string $title = null;
    public ?string $description = null;
    public ?string $status = null;
    public ?string $deadline = null;
    public ?array $tags = [];

    public function __construct(?int $id, ?string $title, ?string $description, ?string $status, ?string $deadline, ?array $tags = [])
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->deadline = $deadline;
        $this->tags = $tags;
    }
}
