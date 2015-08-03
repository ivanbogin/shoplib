<?php

namespace ShopLib\Cart\Entity;

class Cart
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var CartItem[]
     */
    protected $items;

    /**
     * @var float
     */
    protected $subtotal;

    /**
     * @var float
     */
    protected $total;

    /**
     * @var int
     */
    protected $quantity;

    public function __construct()
    {
        $this->items = [];
        $this->total = 0;
        $this->quantity = 0;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get all items in the cart
     *
     * @return CartItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return float
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * @param float $subtotal
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;
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
     * @param float $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
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
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Check if item in the cart
     *
     * @param string $sku
     * @return bool
     */
    public function isItemInCart($sku)
    {
        return array_key_exists($sku, $this->items);
    }

    /**
     * Get item in the cart by SKU.
     * If specified SKU not in cart - return null
     *
     * @param string $sku
     * @return CartItem|null
     */
    public function getItemBySku($sku)
    {
        if (!$this->isItemInCart($sku)) {
            return null;
        }
        return $this->items[$sku];
    }

    /**
     * @param CartItem $item
     */
    public function addItem(CartItem $item)
    {
        $this->items[$item->getProduct()->getSku()] = $item;
    }

    /**
     * @param string $sku
     */
    public function removeItem($sku)
    {
        if ($this->isItemInCart($sku)) {
            unset($this->items[$sku]);
        }
    }
}
