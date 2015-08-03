<?php

namespace ShopLib\Cart\Strategy;

use ShopLib\Cart\Entity\Cart;

interface CalcStrategyInterface
{
    /**
     * Calculate Cart total
     *
     * @param Cart $cart
     */
    public function calculate(Cart $cart);
}
