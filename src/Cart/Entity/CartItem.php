<?php

namespace ShopLib\Cart\Entity;

use ShopLib\Product\Entity\Product;

class CartItem
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * @var int
     */
    protected $qty;

    /**
     * @param Product $product
     * @param int     $qty
     */
    public function __construct(Product $product, $qty)
    {
        $this->product = $product;
        $this->qty = $qty;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
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
     * Item total price (quantity x price)
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->getQty() * $this->getProduct()->getPrice();
    }
}
