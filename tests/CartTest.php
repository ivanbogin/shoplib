<?php

namespace ShopLib\Tests;

use ShopLib\Cart;

class CartTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Cart
     */
    protected $cart;

    protected function setUp()
    {
        $this->cart = new Cart();
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

}
