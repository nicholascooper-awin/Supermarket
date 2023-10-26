<?php
namespace Tests;

require_once('./vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use Model\SalesBook;
use Model\Item;
use Model\Book;

class SalesBookTest extends TestCase
{
    public function test_can_get_SalesBook()
    {
        $salesBook = new SalesBook();
        $this->assertInstanceOf(SalesBook::class, $salesBook);
    }

    public function test_can_add_sale()
    {
        $salesBook = new SalesBook();
        $salesBook->add(new Item(new Book('8726782638726'), 1));
        $this->assertContains(['isbn' => '8726782638726', 'quantity' => 1], $salesBook->list());
    }

}