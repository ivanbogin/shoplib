<?php

namespace ShopLib;

class CartItem
{
    /**
     * @var string
     */
    private $sku;
    /**
     * @var int
     */
    private $qty;
    /**
     * @var float
     */
    private $price;

    /**
     * @param string $sku
     * @param int $qty
     * @param float $price
     */
    public function __construct($sku, $qty, $price)
    {
        $this->sku = $sku;
        $this->qty = $qty;
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return int
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * @param int $qty
     */
    public function setQty($qty)
    {
        $this->qty = $qty;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Item total price (quantity x price)
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->getQty() * $this->getPrice();
    }
}
