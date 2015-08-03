<?php

namespace ShopLib\Stock\Service;

use ShopLib\Stock\Exception\OutOfStockException;

interface StockServiceInterface
{
    /**
     * Get available product stock
     *
     * @param string $sku Product SKU
     * @return int
     */
    public function getStock($sku);

    /**
     * Reserve item quantity.
     * Throws OutOfStockException if there is not enough product in stock.
     *
     * @param string $sku Product SKU
     * @param int $qty How much items to reserve
     * @throws OutOfStockException
     */
    public function reserve($sku, $qty);

    /**
     * Release reserved item
     *
     * @param string $sku Product SKU
     * @param int $qty How much items to release
     */
    public function release($sku, $qty);
}
