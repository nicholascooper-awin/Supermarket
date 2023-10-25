<?php

class Cashier
{
    public function __construct(
        private \DateTime $today,
    )
    {
    }

    public function checkout(Cart $cart, CreditCard $creditCard): bool
    {
        if ($cart->isEmpty()) {
            throw new \InvalidArgumentException('Cart is empty');
        }
        if ($creditCard->isExpired($this->today)) {
            throw new \InvalidArgumentException('Credit card is expired');
        }

        return true;
    }
}