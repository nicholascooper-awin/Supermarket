<?php
namespace Tests;

require_once('./vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use Model\Book;
use Model\Item;


class ItemTest extends TestCase
{
    public function test_can_get_item()
    {
        $book = new Book('8726782638726');
        $item = new Item($book, 1);
        $this->assertInstanceOf(Item::class, $item);
    }

    public function test_can_get_book_and_quantity()
    {
        $book = new Book('8726782638726');
        $item = new Item($book, 1);
        $this->assertEquals($book, $item->getBook());
        $this->assertEquals(1, $item->getQuantity());
    } 
    

    public function test_can_not_add_book_with_quantity_0()
    {
        $book = new Book('8726782638726');
        try {
            $item = new Item($book, 0);
            $this->fail('Expected exception not thrown');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Quantity must be greater than 0', $e->getMessage());
        }
    }   
    

    public function test_can_not_set_quantity_0()
    {
        $book = new Book('8726782638726');
        $item = new Item($book, 1);
        try {
            $item->setQuantity(0);
            $this->fail('Expected exception not thrown');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('Quantity must be greater than 0', $e->getMessage());
        }
    }     

}

