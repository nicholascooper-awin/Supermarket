<?php
namespace Tests\Facade;

require_once('./vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use Facade\AFacade;
use Model\Cart;
use Repo\CartRepo;

class AFacadeTest extends TestCase
{
    private CartRepo $cartRepo;
    public function setUp(): void
    {
        $this->cartRepo = new CartRepo();
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
        $cartId = $facade->createCart();


        $cart = $this->cartRepo->findCart($cartId);
        $this->assertTrue($cart->isEmpty());
    }

    public function test_can_add_to_cart()
    {
        $facade = $this->getAFacade();
        $cartId = $facade->createCart();
        $this->assertTrue($facade->addToCart($cartId, '8726782638726', 1));

        $cart = $this->cartRepo->findCart($cartId);
        $this->assertFalse($cart->isEmpty());
    }

    private function getAFacade(): AFacade
    {
        return new AFacade($this->cartRepo);
    }

}

