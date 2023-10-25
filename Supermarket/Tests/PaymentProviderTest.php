<?php
namespace Tests;

require_once('./vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use PaymentProvider;
use CreditCard;

class PaymentProviderTest extends TestCase
{
    public function test_can_get_PaymentProvider()
    {
        $paymentProvider = new PaymentProvider();
        $this->assertInstanceOf(PaymentProvider::class, $paymentProvider);
    }

    public function test_can_use_creditcard()
    {
        $dateTime = new \DateTime();
        $creditCard = new CreditCard($dateTime->format('mY'));
        $paymentProvider = new PaymentProvider();

        $this->assertTrue($paymentProvider->pay($creditCard, 1000));
    }

}