<?php

class CartTest extends PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        $cart = new Cart();
        $this->assertEmpty($cart);
    }
}
