<?php

class Checkout
{

    public function __construct(private Cart $cart)
    {
    }

    public function perform(): bool
    {
        if ($this->cart->isEmpty()) {
            throw new \InvalidArgumentException('Cart is empty');
        }

        return true;
    }
}