<?php
namespace Model;

class Cashier
{
    public function __construct(
        private \DateTime $today,
        private PaymentProvider $paymentProvider,
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
        try {
            $this->paymentProvider->pay($creditCard, $this->getTotal($cart));
        } catch (\UnexpectedValueException $e) {
            throw new \UnexpectedValueException('Sorry you are poor');
        }

        return true;
    }

    public function getTotal(Cart $cart): int
    {
        return $cart->getTotal();
    }
}