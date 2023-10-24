<?php
namespace Tests;

require_once('./vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use Catalogue;
use Book;


class CatalogueTest extends TestCase
{
    public function test_can_get_catalogue()
    {
        $catalogue = new Catalogue();
        $this->assertInstanceOf(Catalogue::class, $catalogue);
    }

    public function test_book_in_catalogue()
    {
        $book = new Book('8726782638726');
        $catalogue = new Catalogue();
        $this->assertTrue($catalogue->isAllowed($book));
    }


    public function test_book_not_in_catalogue()
    {
        $book = new Book('11111');
        $catalogue = new Catalogue();
        $this->assertFalse($catalogue->isAllowed($book));
    }    


}

