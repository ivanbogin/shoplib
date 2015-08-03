<?php

namespace ShopLib\Discount\Service;

interface DiscountRepositoryInterface
{
    /**
     * Get coupon by code
     *
     * @param string $code Coupon code
     * @return mixed
     */
    public function getCouponByCode($code);
}
