<?php

namespace ShopLib\Cart\Strategy;

use ShopLib\Cart\Entity\Cart;

/**
 * Fixed discount price strategy.
 * To use discount, total price must be 2 times greater than discount
 */
class PriceCalcStrategy extends NormalCalcStrategy
{
    /**
     * Calculate Cart total
     *
     * @param Cart $cart
     */
    public function calculate(Cart $cart)
    {
        parent::calculate($cart);
        $total = $cart->getTotal();
        $discount = $cart->getDiscount()->getAmount();

        // total price must be 2 times greater than discount
        if ($total < $discount * 2) {
            return;
        }

        $total -= $discount;
        $cart->setTotal($total);
    }

}
