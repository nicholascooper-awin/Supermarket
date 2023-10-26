<?php

namespace Facade;

use Model\Book;
use Model\Item;
use Repo\CartRepo;

class AFacade
{

    public function __construct(private CartRepo $cartRepo)
    {
    }

    public function createCart(): int
    {
        $cart = $this->cartRepo->createCart();
        return $cart->getId();
    }

    public function addToCart(int $cartId, string $isbn, int $quantity): bool
    {
        $cart = $this->cartRepo->findCart($cartId);
        $cart->addItem(new Item(new Book($isbn), $quantity));
        return true;
    }

}