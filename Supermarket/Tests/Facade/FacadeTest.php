<?php
namespace Tests\Facade;

require_once('./vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use Facade\AFacade;
use Repo\CartRepo;
use Model\SalesBook;

class AFacadeTest extends TestCase
{
    private CartRepo $cartRepo;
    private SalesBook $salesBook;
    public function setUp(): void
    {
        $this->cartRepo = new CartRepo();
        $this->salesBook = new SalesBook(); 
        parent::setUp();
    }


    public function test_can_get_facade()
    {
        $facade = $this->getAFacade();
        $this->assertInstanceOf(AFacade::class, $facade);
    }

    public function test_can_create_cart()
    {
        $facade = $this->getAFacade();
        $cartId = $facade->createCart('clientId', 'password');

        $list = $facade->listCart($cartId);
        $this->assertEquals([], $list);
    }

    public function test_cannot_create_cart_bad_client()
    {
        $facade = $this->getAFacade();

        try {
            $facade->createCart('clientId', 'not_password');
            $this->fail('Should have thrown an exception');
        } catch (\InvalidArgumentException $exception) {
            $this->assertEquals('Bad client credentials', $exception->getMessage());
        }
    }

    public function test_can_add_to_cart()
    {
        $facade = $this->getAFacade();
        $cartId = $facade->createCart('clientId', 'password');
        $this->assertTrue($facade->addToCart($cartId, '8726782638726', 1));

        $list = $facade->listCart($cartId);
        $this->assertNotEmpty($list);
    }


    public function test_can_list_cart_one()
    {
        $facade = $this->getAFacade();
        $cartId = $facade->createCart('clientId', 'password');
        $facade->addToCart($cartId, '8726782638726', 1);
        $list = $facade->listCart($cartId);

        $this->assertContains(['8726782638726' => 1], $list);
    }


    public function test_can_list_cart_many()
    {
        $facade = $this->getAFacade();
        $cartId = $facade->createCart('clientId', 'password');
        $facade->addToCart($cartId, '8726782638726', 1);
        $facade->addToCart($cartId, '8726782638727', 8);
        $list = $facade->listCart($cartId);

        $this->assertContains(['8726782638726' => 1], $list);
        $this->assertContains(['8726782638727' => 8], $list);
    }

    public function test_can_checkout_cart()
    {
        $facade = $this->getAFacade();
        $cartId = $facade->createCart('clientId', 'password');
        $facade->addToCart($cartId, '8726782638726', 1);
        $facade->addToCart($cartId, '8726782638727', 8);
        $this->assertTrue($facade->checkoutCart($cartId, $this->makeValidCreditCardMonthYear()));
        //todo salesbook
    }

    public function test_cannot_checkout_cart()
    {
        $facade = $this->getAFacade();
        $cartId = $facade->createCart('clientId', 'password');
        $facade->addToCart($cartId, '8726782638726', 1);
        $facade->addToCart($cartId, '8726782638727', 8);
        try {
            $facade->checkoutCart($cartId, $this->makeInvalidCreditCardMonthYear());
            $this->fail('Should have thrown an exception');
        } catch (\InvalidArgumentException $exception) {
            $this->assertEquals('Credit card is expired', $exception->getMessage());
        //todo salesbook
    }
    }    

    private function getAFacade(): AFacade
    {
        return new AFacade($this->cartRepo, $this->salesBook);
    }

    private function makeValidCreditCardMonthYear(): string
    {
        $dateTime = new \DateTime();
        $dateTime->modify('+1 year');
        return $dateTime->format('mY');
    }

    private function makeInvalidCreditCardMonthYear(): string
    {
        $dateTime = new \DateTime();
        $dateTime->modify('-1 year');
        return $dateTime->format('mY');
    }    
}

