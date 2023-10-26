<?php
namespace Model;

class Item
{
    private int $quantity;

    public function __construct(private Book $book, int $quantity)
    {
        $this->setQuantity($quantity);
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than 0');
        }
        $this->quantity = $quantity;
    }
}