<?php
namespace Tests;

require_once('./vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use Model\Cart;
use Model\Book;
use Model\Item;

class CartTest extends TestCase
{
    public function test_can_get_cart()
    {
        $cart = new Cart();
        $this->assertInstanceOf(Cart::class, $cart);
    }

    public function test_cart_is_created_empty()
    {
        $cart = new Cart();
        $this->assertTrue($cart->isEmpty());
    }


    public function test_cart_can_list_items()
    {
        $cart = new Cart();
        $this->assertIsList($cart->getItems());
    }


    public function test_cart_is_not_empty()
    {
        $cart = new Cart();
        $book = new Book('8726782638726');
        $item = new Item($book, 1);
        $cart->addItem($item);
        $this->assertFalse($cart->isEmpty());
    }

    public function test_cart_can_add_item_and_list_items()
    {
        $cart = new Cart();
        $book = new Book('8726782638726');
        $item = new Item($book, 1);
        $cart->addItem($item);
        $this->assertContains($item, $cart->getItems());
    }


    public function test_cart_can_add_book_with_quanity_2_and_list_items()
    {
        $cart = new Cart();
        $book = new Book('8726782638726');
        $item = new Item($book, 2);
        $cart->addItem($item);
        $this->assertContains($item, $cart->getItems());
    }


    public function test_cart_can_add_2_book_and_list_items()
    {
        $cart = new Cart();
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
        $cart = new Cart();
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
        $cart = new Cart();
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
        $cart = new Cart();
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
        $cart = new Cart();
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
        $cart = new Cart();
        $this->assertEquals(0, $cart->getTotal());
    }
    public function test_can_get_total_for_one_book()
    {
        $cart = new Cart();
        $book = new Book('8726782638726');
        $item = new Item($book, 1);
        $cart->addItem($item);
        $this->assertEquals(5000, $cart->getTotal());
    }
    public function test_can_get_total_for_many_books()
    {
        $cart = new Cart();
        $cart->addItem(new Item(new Book('8726782638726'), 5));
        $cart->addItem(new Item(new Book('8726782638727'), 2));
        $this->assertEquals(31000, $cart->getTotal());
    }


}

