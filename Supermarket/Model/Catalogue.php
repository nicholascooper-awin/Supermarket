<?php
namespace Model;

class Catalogue
{

    private array $books = [
        '8726782638726' => 5000,
        '8726782638727' => 3000,
        '8726782638728' => 2000,
    ];

    public function isAllowed(Book $book): bool
    {
        return in_array($book->getIsbn(), [
            '8726782638726',
            '8726782638727',
        ]);
    }

    public function getBookPrice(Book $book): int
    {
        return $this->books[$book->getIsbn()];
    }
}