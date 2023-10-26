<?php
namespace Repo;

use Model\Cart;

class CartRepo
{
    private ?Cart $cart = null;

    public function createCart(): Cart
    {
        $this->cart = new Cart();
        $id = 1;
        $this->cart->setId($id);
        return $this->cart;
    }

    public function findCart(int $id): Cart
    {
        return $this->cart;
    }
}