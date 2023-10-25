<?php
class PaymentProvider
{
    public function pay(CreditCard $creditCard, int $amount): bool
    {
        return true;
    }
}