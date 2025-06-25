<?php

namespace App\Model;

use App\Core\Response;
use App\Domain\Tag;

class TagUpdateResponse extends Response
{
    public Tag $tag;
}
