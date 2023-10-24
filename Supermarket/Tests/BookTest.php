<?php
namespace Tests;

require_once('./vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use Book;


class BookTest extends TestCase
{
    public function test_can_get_book()
    {
        $book = new Book('8726782638726');
        $this->assertInstanceOf(Book::class, $book);
    }

    public function test_can_get_book_isbn()
    {
        $isbn = '3782627836287';
        $book = new Book($isbn);
        $this->assertEquals($isbn, $book->getIsbn());
    }


}

