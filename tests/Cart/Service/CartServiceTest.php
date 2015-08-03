<?php

namespace ShopLib\Cart\Service\Tests;

use ShopLib\Cart\Entity\Cart;
use ShopLib\Cart\Repository\CartRepositoryInterface;
use ShopLib\Cart\Service\CartService;
use ShopLib\Stock\Service\StockServiceInterface;

class CartServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var CartService
     */
    protected $cartService;

    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var StockServiceInterface
     */
    protected $stockService;

    protected function setUp()
    {
        $this->cart = new Cart();
        $this->cartRepository = $this->getMock(CartRepositoryInterface::class);
        $this->stockService = $this->getMock(StockServiceInterface::class);
        $this->cartService = new CartService($this->cartRepository, $this->stockService);
    }

    public function testCartIsEmpty()
    {
        $this->assertCart(0, 0, 0);
    }

    public function testAddItem()
    {
        $this->cart->addItem('B00BGA9WK2', 1, 399.95);
        $this->cart->addItem('B00FNQUN7Q', 2, 54.95);
        $this->assertCart(2, 3, 509.85);
    }

    public function testAddItemTwice()
    {
        $this->cart->addItem('B00FNQUN7Q', 1, 54.95);
        // Also price was changed
        $this->cart->addItem('B00FNQUN7Q', 1, 60);
        $this->assertCart(1, 2, 120);
    }

    public function testUpdateQty()
    {
        $this->cart->addItem('B00FNQUN7Q', 1, 54.95);
        $this->cart->updateItemQty('B00FNQUN7Q', 3);
        $this->assertCart(1, 3, 164.85);
    }

    public function testUpdateQtyToZero()
    {
        $this->cart->addItem('B00BGA9WK2', 1, 399.95);
        $this->cart->addItem('B00FNQUN7Q', 2, 54.95);

        $this->cart->updateItemQty('B00BGA9WK2', 0);
        $this->assertCart(1, 2, 109.9);

        $this->cart->updateItemQty('B00FNQUN7Q', 0);
        $this->assertCart(0, 0, 0);
    }

    /**
     * Assert current cart
     *
     * @param int $count Number of positions in cart
     * @param int $quantity Total number of items
     * @param float $total Total price
     */
    protected function assertCart($count, $quantity, $total)
    {
        $this->assertCount($count, $this->cart->getItems());
        $this->assertEquals($quantity, $this->cart->getQuantity());
        $this->assertEquals($total, $this->cart->getTotal());
    }

}
