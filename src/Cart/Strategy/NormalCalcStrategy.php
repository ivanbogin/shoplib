<?php

namespace ShopLib\Cart\Strategy;

use ShopLib\Cart\Entity\Cart;

/**
 * Normal calculation strategy without discount
 */
class NormalCalcStrategy implements CalcStrategyInterface
{
    /**
     * Calculate Cart total
     *
     * @param Cart $cart
     */
    public function calculate(Cart $cart)
    {
        $total = 0;
        $quantity = 0;
        foreach ($cart->getItems() as $item) {
            $total += $item->getTotal();
            $quantity += $item->getQty();
        }
        $cart->setSubtotal($total);
        $cart->setTotal($total);
        $cart->setQuantity($quantity);
    }
}
