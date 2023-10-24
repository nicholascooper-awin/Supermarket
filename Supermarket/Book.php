<?php

class Book
{
    public function __construct(private string $isbn)
    {
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }
}