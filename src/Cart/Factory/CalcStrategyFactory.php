<?php

namespace ShopLib\Cart\Factory;

use ShopLib\Cart\Strategy\CalcStrategyInterface;
use ShopLib\Cart\Strategy\NormalCalcStrategy;
use ShopLib\Cart\Strategy\PriceCalcStrategy;
use ShopLib\Discount\Entity\Discount;

class CalcStrategyFactory
{
    /**
     * Get cart calculation strategy according to discount
     *
     * @param Discount|null $discount
     *
     * @return CalcStrategyInterface
     */
    public function getStrategy(Discount $discount = null)
    {
        if ($discount == null) {
            return new NormalCalcStrategy();
        }

        $type = $discount->getType();
        if ($type == Discount::TYPE_PRICE) {
            return new PriceCalcStrategy();
        }

        return new NormalCalcStrategy();
    }
}
