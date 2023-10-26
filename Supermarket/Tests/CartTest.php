<?php
namespace Tests;

require_once('./vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use Model\Cart;
use Model\Book;
use Model\Item;
use Model\DateTimeProvider;

class CartTest extends TestCase
{
    public function test_can_get_cart()
    {
        $cart = $this->getCart();
        $this->assertInstanceOf(Cart::class, $cart);
    }

    public function test_cart_is_created_empty()
    {
        $cart = $this->getCart();
        $this->assertTrue($cart->isEmpty());
    }


    public function test_cart_can_list_items()
    {
        $cart = $this->getCart();
        $this->assertIsList($cart->getItems());
    }


    public function test_cart_is_not_empty()
    {
        $cart = $this->getCart();
        $book = new Book('8726782638726');
        $item = new Item($book, 1);
        $cart->addItem($item);
        $this->assertFalse($cart->isEmpty());
    }

    public function test_cart_can_add_item_and_list_items()
    {
        $cart = $this->getCart();
        $book = new Book('8726782638726');
        $item = new Item($book, 1);
        $cart->addItem($item);
        $this->assertContains($item, $cart->getItems());
    }


    public function test_cart_can_add_book_with_quanity_2_and_list_items()
    {
        $cart = $this->getCart();
        $book = new Book('8726782638726');
        $item = new Item($book, 2);
        $cart->addItem($item);
        $this->assertContains($item, $cart->getItems());
    }


    public function test_cart_can_add_2_book_and_list_items()
    {
        $cart = $this->getCart();
        $book1 = new Book('8726782638726');
        $book2 = new Book('8726782638727');
        $item1 = new Item($book1, 2);
        $item2 = new Item($book2, 3);
        $cart->addItem($item1);
        $cart->addItem($item2);
        $this->assertContains($item1, $cart->getItems());
        $this->assertContains($item2, $cart->getItems());
    }


    public function test_cart_can_add_1_book_twice_same_object_and_update_quantity()
    {
        $cart = $this->getCart();
        $book1 = new Book('8726782638726');
        $item1 = new Item($book1, 2);
        $item2 = new Item($book1, 3);
        $cart->addItem($item1);
        $cart->addItem($item2);
        $this->assertContains($item1, $cart->getItems());
        $this->assertEquals(5, $item1->getQuantity());
        $this->assertNotContains($item2, $cart->getItems());
    }

    public function test_cart_can_add_1_book_twice_diff_object_and_update_quantity()
    {
        $cart = $this->getCart();
        $book1 = new Book('8726782638726');
        $book2 = new Book('8726782638726');
        $item1 = new Item($book1, 6);
        $item2 = new Item($book2, 9);
        $cart->addItem($item1);
        $cart->addItem($item2);
        $this->assertContains($item1, $cart->getItems());
        $this->assertEquals(15, $item1->getQuantity());
        $this->assertNotContains($item2, $cart->getItems());
    }


    public function test_can_not_add_non_TusLibros_book()
    {
        $cart = $this->getCart();
        $book = new Book('11111');
        $item = new Item($book, 1);
        try {
            $cart->addItem($item);
            $this->fail('Expected exception not thrown');
        } catch (\UnexpectedValueException $e) {
            $this->assertEquals('Book not allowed', $e->getMessage());
            $this->assertNotContains($item, $cart->getItems());
        }
    }

    public function test_can_not_add_non_TusLibros_book_again()
    {
        $cart = $this->getCart();
        $book = new Book('22222');
        $item = new Item($book, 1);
        try {
            $cart->addItem($item);
            $this->fail('Expected exception not thrown');
        } catch (\UnexpectedValueException $e) {
            $this->assertEquals('Book not allowed', $e->getMessage());
            $this->assertNotContains($item, $cart->getItems());
        }
    }

    public function test_can_get_empty_total_is_0()
    {
        $cart = $this->getCart();
        $this->assertEquals(0, $cart->getTotal());
    }
    public function test_can_get_total_for_one_book()
    {
        $cart = $this->getCart();
        $book = new Book('8726782638726');
        $item = new Item($book, 1);
        $cart->addItem($item);
        $this->assertEquals(5000, $cart->getTotal());
    }
    public function test_can_get_total_for_many_books()
    {
        $cart = $this->getCart();
        $cart->addItem(new Item(new Book('8726782638726'), 5));
        $cart->addItem(new Item(new Book('8726782638727'), 2));
        $this->assertEquals(31000, $cart->getTotal());
    }

    public function test_cart_has_not_expired()
    {
        $cart = $this->getCart();
        $this->assertFalse($cart->hasExpired(new \DateTime()));
    }
    public function test_cart_has_expired()
    {
        $dateTimeProvider = new DateTimeProvider();
        $now = $dateTimeProvider->getNow();
        $cart = new Cart($dateTimeProvider);
        $futureDateTime = clone $now;
        $futureDateTime->modify('+30 minutes +1 second');
        $this->assertTrue($cart->hasExpired($futureDateTime));
    }

    public function test_cart_has_not_expired_with_a_second_left()
    {
        $dateTimeProvider = new DateTimeProvider();
        $now = $dateTimeProvider->getNow();
        $cart = new Cart($dateTimeProvider);
        $futureDateTime = clone $now;
        $futureDateTime->modify('+30 minutes -1 second');
        $this->assertFalse($cart->hasExpired($futureDateTime));
    } 

    public function test_cart_has_expired_after_last_item()
    {
        $dateTimeProvider = new DateTimeProvider();
        $now = $dateTimeProvider->getNow();
        $cart = new Cart($dateTimeProvider);
        $cart->addItem(new Item(new Book('8726782638726'), 1));
        $cart->addItem(new Item(new Book('8726782638726'), 1));
        $futureDateTime = clone $now;
        $futureDateTime->modify('+30 minutes +1 second');
        $this->assertTrue($cart->hasExpired($futureDateTime));
    }    
    
    private function getCart(): Cart
    {
        return new Cart(new DateTimeProvider());
    }

}

