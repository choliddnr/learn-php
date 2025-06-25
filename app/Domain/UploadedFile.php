<?php

namespace App\Domain;

class UploadedFile
{
    public string $name;
    public string $type;
    public string $tmp_name;
    public int $error;
    public int $size;

    public function __construct(array $file)
    {
        $this->name = $file['name'] ?? '';
        $this->type = $file['type'] ?? '';
        $this->tmp_name = $file['tmp_name'] ?? '';
        $this->error = $file['error'] ?? UPLOAD_ERR_NO_FILE;
        $this->size = $file['size'] ?? 0;
    }
}
