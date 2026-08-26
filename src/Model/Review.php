<?php

namespace App\Model;

class Review{

    public function __construct(
        private string $author,
        private string $comment
    )
    {
    }

    public function getAuthor():string
    {
        return $this->author;
    }

    public function getComment():string
    {
        return $this->comment;
    }
}
