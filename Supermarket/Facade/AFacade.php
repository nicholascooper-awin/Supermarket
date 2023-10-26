<?php

namespace Facade;

use Model\Book;
use Model\Item;
use Repo\CartRepo;
use Model\CreditCard;
use Model\Cashier;
use Model\PaymentProvider;
use Model\SalesBook;

class AFacade
{

    public function __construct(private CartRepo $cartRepo, private SalesBook $salesBook)
    {
    }

    public function createCart(string $clientId, string $password): int
    {
        if ($password !== 'password') {
            throw new \InvalidArgumentException('Bad client credentials');
        }
        $cart = $this->cartRepo->createCart();
        return $cart->getId();
    }

    public function addToCart(int $cartId, string $isbn, int $quantity): bool
    {
        $cart = $this->cartRepo->findCart($cartId);
        $cart->addItem(new Item(new Book($isbn), $quantity));
        return true;
    }

    public function listCart(int $cartId): array
    {
        $cart = $this->cartRepo->findCart($cartId);
        $list = [];
        foreach ($cart->getItems() as $item) {
            $list[] = [$item->getBook()->getIsbn() => $item->getQuantity()];
        }
        return $list;
    }

    public function checkoutCart(int $cartId, string $creditCardMonthYear): bool
    {
        $cart = $this->cartRepo->findCart($cartId);

        $cashier = new Cashier(new \DateTime(), new PaymentProvider());
        return $cashier->checkout($cart, new CreditCard($creditCardMonthYear));
    }

}