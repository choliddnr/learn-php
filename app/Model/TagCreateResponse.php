<?php

namespace App\Model;

use App\Core\Response;
use App\Domain\Tag;

class TagCreateResponse extends Response
{
    public Tag $tag;
}
