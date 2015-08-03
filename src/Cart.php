<?php

namespace ShopLib;

class Cart
{
    /**
     * @var CartItem[]
     */
    private $items;

    /**
     * @var float
     */
    private $total;

    /**
     * @var int
     */
    private $quantity;

    public function __construct()
    {
        $this->items = [];
        $this->total = 0;
        $this->quantity = 0;
    }

    /**
     * Get all items in the cart
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add item to cart or add additional qty
     *
     * @param string $sku
     * @param int $qty
     * @param float $price
     */
    public function addItem($sku, $qty, $price)
    {
        if ($this->isItemInCart($sku)) {
            $item = $this->getItemBySku($sku);
            $item->setPrice($price);
            $item->setQty($item->getQty() + $qty);
        } else {
            $item = new CartItem($sku, $qty, $price);
            $this->items[$sku] = $item;
        }
        $this->recalculate();
    }

    /**
     * Total price
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Total number of items
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Update item quantity
     *
     * @param string $sku
     * @param int $qty
     * @throws \OutOfBoundsException
     */
    public function updateItemQty($sku, $qty)
    {
        if (!$this->isItemInCart($sku)) {
            throw new \OutOfBoundsException(sprintf('item %s not found in the cart', $sku));
        }
        if ($qty === 0) {
            $this->removeItem($sku);
        } else {
            $this->getItemBySku($sku)->setQty($qty);
            $this->recalculate();
        }
    }

    /**
     * Remove item from the cart
     *
     * @param string $sku
     */
    public function removeItem($sku)
    {
        unset($this->items[$sku]);
        $this->recalculate();
    }

    /**
     * Recalculate cart total and quantity
     */
    private function recalculate()
    {
        $this->total = 0;
        $this->quantity = 0;
        foreach ($this->items as $item) {
            $this->total += $item->getTotal();
            $this->quantity += $item->getQty();
        }
    }

    /**
     * Check if item in the cart
     *
     * @param string $sku
     * @return bool
     */
    private function isItemInCart($sku)
    {
        return array_key_exists($sku, $this->items);
    }

    /**
     * Get item in the cart by SKU
     *
     * @param string $sku
     * @return CartItem
     */
    private function getItemBySku($sku)
    {
        return $this->items[$sku];
    }
}
