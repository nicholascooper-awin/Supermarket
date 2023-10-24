<?php
namespace Tests;

require_once('./vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use Checkout;
use Cart;
use Item;
use Book;

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
            $checkout->perform();
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

        $this->assertTrue($checkout->perform());
    }


    public function test_checkout_can_checkout_many_items()
    {
        $cart = new Cart();
        $checkout = new Checkout($cart);
        $cart->addItem(new Item(new Book('8726782638726'), 1));
        $cart->addItem(new Item(new Book('8726782638727'), 8));

        $this->assertTrue($checkout->perform());
    }
}