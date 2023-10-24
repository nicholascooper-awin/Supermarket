<?php
class Cart
{
    private array $items = [];

    private Catalogue $catalogue;

    public function __construct()
    {
        $this->catalogue = new Catalogue();
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(Item $item): void
    {
        if (!$this->catalogue->isAllowed($item->getBook())) {
            throw new \UnexpectedValueException('Book not allowed');
        }
        if (!is_null($foundItem = $this->findItemByBook($item->getBook()))) {
            $foundItem->setQuantity($foundItem->getQuantity() + $item->getQuantity());
            return;
        }
        $this->items[] = $item;
    }

    private function findItemByBook(Book $book): ?Item
    {
        foreach ($this->items as $item) {
            if ($item->getBook()->getIsbn() === $book->getIsbn()) {
                return $item;
            }
        }
        return null;
    }

    public function getId(): int
    {
        return 1;
    }
}