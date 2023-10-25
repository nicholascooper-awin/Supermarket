<?php
namespace Tests;

require_once('./vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use Checkout;
use Cart;
use Item;
use Book;
use CreditCard;
use DateTimeProvider;
use DateTimeProviderInterface;

class CheckoutTest extends TestCase
{
    public function test_can_get_checkout()
    {
        $checkout = new Checkout(new Cart);
        $this->assertInstanceOf(Checkout::class, $checkout);
    }

    public function test_checkout_can_not_checkout_zero_items()
    {
        $checkout = new Checkout(new Cart());

        try {
            $checkout->perform($this->makeValidCreditCard());
            $this->fail('Expected exception not thrown');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Cart is empty', $e->getMessage());
        }
    }

    public function test_checkout_can_checkout_one_item()
    {
        $cart = new Cart();
        $checkout = new Checkout($cart);
        $cart->addItem(new Item(new Book('8726782638726'), 1));

        $this->assertTrue($checkout->perform($this->makeValidCreditCard()));
    }


    public function test_checkout_can_checkout_many_items()
    {
        $cart = new Cart();
        $checkout = new Checkout($cart);
        $cart->addItem(new Item(new Book('8726782638726'), 1));
        $cart->addItem(new Item(new Book('8726782638727'), 8));

        $this->assertTrue($checkout->perform($this->makeValidCreditCard()));
    }


    public function test_checkout_can_accept_creditcard()
    {
        $cart = new Cart();
        $checkout = new Checkout($cart);
        $cart->addItem(new Item(new Book('8726782638726'), 1));

        $this->assertTrue($checkout->perform($this->makeValidCreditCard()));
    }


    public function test_checkout_cannot_accept_expired_creditcard()
    {
        $cart = new Cart();
        $checkout = new Checkout($cart);
        $cart->addItem(new Item(new Book('8726782638726'), 1));

        try {
            $checkout->perform($this->makeInvalidCreditCard());
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Credit card is expired', $e->getMessage());
        }
    }

    public function test_checkout_can_accept_creditcard_just_before_expire()
    {
        $cart = new Cart();
        $checkout = new Checkout($cart);
        $cart->addItem(new Item(new Book('8726782638726'), 1));

        $futureDate = new \DateTime('2023-10-31 23:59:59');
        $creditcard = new CreditCard($this->mockDateTimeProvider($futureDate), $futureDate->format('mY'));
        $this->assertTrue($checkout->perform($creditcard));
    }

    // public function test_checkout_cannot_accept_creditcard_just_after_expire()
    // {
    //     $cart = new Cart();
    //     $checkout = new Checkout($cart);
    //     $cart->addItem(new Item(new Book('8726782638726'), 1));

    //     $dateTimeProvider = new DateTimeProviderMock(new \DateTime('2023-11-01 00:00:01'));
    //     $futureDate = new \DateTime('2023-10-31');
    //     $creditcard = new CreditCard($dateTimeProvider, $futureDate->format('mY'));

    //     try {
    //         $checkout->perform($creditcard);
    //         $this->fail('Expected exception not thrown');
    //     } catch (\InvalidArgumentException $e) {
    //         $this->assertEquals('Credit card is expired', $e->getMessage());
    //     }
    // }


    private function makeValidCreditCard(): CreditCard
    {
        $futureDate = new \DateTime();
        $futureDate->modify('+1 year');
        return new CreditCard(new DateTimeProvider(), $futureDate->format('mY'));
    }

    private function makeInvalidCreditCard(): CreditCard
    {
        $expiredDate = new \DateTime();
        $expiredDate->modify('-1 year');
        return new CreditCard(new DateTimeProvider(), $expiredDate->format('mY'));
    }

    private function mockDateTimeProvider(\DateTime $expiredDate)
    {
        $dateTimeProviderMock = $this->createMock(DateTimeProvider::class);
        $dateTimeProviderMock->method('getDateTime')
            ->willReturn($expiredDate);
        return $dateTimeProviderMock;
    }
}