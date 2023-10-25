<?php

class Checkout
{

    public function __construct(private Cart $cart)
    {
    }

    public function perform(CreditCard $creditCard): bool
    {
        if ($this->cart->isEmpty()) {
            throw new \InvalidArgumentException('Cart is empty');
        }
        if ($creditCard->isExpired()) {
            throw new \InvalidArgumentException('Credit card is expired');
        }

        return true;
    }
}