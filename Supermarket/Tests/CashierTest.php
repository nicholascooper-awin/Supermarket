<?php
namespace Tests;

require_once('./vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use Cashier;
use Cart;
use Item;
use Book;
use CreditCard;
use DateTimeProvider;
use DateTimeProviderInterface;

class CashierTest extends TestCase
{
    public function test_can_get_Cashier()
    {
        $cashier = new Cashier(new \DateTime());
        $this->assertInstanceOf(Cashier::class, $cashier);
    }

    public function test_Cashier_can_not_Cashier_zero_items()
    {
        $cashier = new Cashier(new \DateTime());

        try {
            $cashier->checkout(new Cart(), $this->makeValidCreditCard());
            $this->fail('Expected exception not thrown');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Cart is empty', $e->getMessage());
        }
    }

    public function test_Cashier_can_Cashier_one_item()
    {
        $cart = new Cart();
        $cart->addItem(new Item(new Book('8726782638726'), 1));

        $cashier = new Cashier(new \DateTime());
        $this->assertTrue($cashier->checkout($cart, $this->makeValidCreditCard()));
    }


    public function test_Cashier_can_Cashier_many_items()
    {
        $cart = new Cart();
        $cart->addItem(new Item(new Book('8726782638726'), 1));
        $cart->addItem(new Item(new Book('8726782638727'), 8));

        $cashier = new Cashier(new \DateTime());
        $this->assertTrue($cashier->checkout($cart, $this->makeValidCreditCard()));
    }


    public function test_Cashier_can_accept_creditcard()
    {
        $cart = new Cart();
        $cart->addItem(new Item(new Book('8726782638726'), 1));

        $cashier = new Cashier(new \DateTime());
        $this->assertTrue($cashier->checkout($cart, $this->makeValidCreditCard()));
    }


    public function test_Cashier_cannot_accept_expired_creditcard()
    {
        $cart = new Cart();
        $cart->addItem(new Item(new Book('8726782638726'), 1));
        $cashier = new Cashier(new \DateTime());

        try {
            $cashier->checkout($cart, $this->makeInvalidCreditCard());
            $this->fail('Expected exception not thrown');;
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Credit card is expired', $e->getMessage());
        }
    }

    public function test_Cashier_can_accept_creditcard_just_before_expire()
    {
        $futureDate = new \DateTime('2023-10-31 23:59:59');

        $cart = new Cart();
        $cashier = new Cashier($futureDate);
        $cart->addItem(new Item(new Book('8726782638726'), 1));

        $creditcard = new CreditCard($futureDate->format('mY'));
        $this->assertTrue($cashier->checkout($cart, $creditcard));
    }

    public function test_Cashier_cannot_accept_creditcard_just_after_expire()
    {
        $futureDate = new \DateTime('2023-11-01 00:00:01');
        $expiredDate = new \DateTime('2023-10-31 23:59:59');

        $cart = new Cart();
        $cashier = new Cashier($futureDate);
        $cart->addItem(new Item(new Book('8726782638726'), 1));

        $creditcard = new CreditCard($expiredDate->format('mY'));

        try {
            $cashier->checkout($cart, $creditcard);
            $this->fail('Expected exception not thrown');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Credit card is expired', $e->getMessage());
        }
    }


    private function makeValidCreditCard(): CreditCard
    {
        $futureDate = new \DateTime();
        $futureDate->modify('+1 year');
        return new CreditCard($futureDate->format('mY'));
    }

    private function makeInvalidCreditCard(): CreditCard
    {
        $expiredDate = new \DateTime();
        $expiredDate->modify('-1 year');
        return new CreditCard($expiredDate->format('mY'));
    }
}