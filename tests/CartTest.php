<?php

namespace ShopLib\Tests;

use ShopLib\Cart;

class CartTest extends \PHPUnit_Framework_TestCase
{
    public function testCartIsEmpty()
    {
        $cart = new Cart();

        $this->assertEmpty($cart->getItems());
        $this->assertEquals(0, $cart->getQuantity());
        $this->assertEquals(0, $cart->getTotal());
    }

    public function testAddItem()
    {
        $cart = new Cart();
        $cart->addItem('B00BGA9WK2', 1, 399.95);
        $cart->addItem('B00FNQUN7Q', 2, 54.95);

        $this->assertCount(2, $cart->getItems());
        $this->assertEquals(3, $cart->getQuantity());
        $this->assertEquals(509.85, $cart->getTotal());
    }

    public function testAddItemTwice()
    {
        $cart = new Cart();
        $cart->addItem('B00FNQUN7Q', 1, 54.95);
        // Also price was changed
        $cart->addItem('B00FNQUN7Q', 1, 60);

        $this->assertCount(1, $cart->getItems());
        $this->assertEquals(2, $cart->getQuantity());
        $this->assertEquals(120, $cart->getTotal());
    }

    public function testUpdateQty()
    {
        $cart = new Cart();
        $cart->addItem('B00FNQUN7Q', 1, 54.95);
        $cart->updateItemQty('B00FNQUN7Q', 3);

        $this->assertCount(1, $cart->getItems());
        $this->assertEquals(3, $cart->getQuantity());
        $this->assertEquals(164.85, $cart->getTotal());
    }
}
