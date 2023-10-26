<?php
namespace Repo;

use Model\Cart;

class CartRepo
{
    private array $carts = [];

    public function createCart(): Cart
    {
        $id = 1;
        $this->carts[$id] = new Cart();
        $this->carts[$id]->setId($id);
        return $this->carts[$id];
    }

    public function findCart(int $id): Cart
    {
        return $this->carts[$id];
    }
}