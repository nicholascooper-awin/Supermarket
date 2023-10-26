<?php
namespace Model;

class SalesBook
{

    private array $sales = [];

    public function add(Item $item): void
    {
        $this->sales[] = [
            'isbn' => $item->getBook()->getIsbn(),
            'quantity' => $item->getQuantity(),
        ];
    }

    public function list(): array
    {
        return $this->sales;
    }

}