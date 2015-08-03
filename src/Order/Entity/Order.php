<?php

namespace ShopLib\Order\Entity;

/**
 * Completed Order
 *
 * Class Order
 * @package ShopLib\Order\Entity
 */
class Order
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var float
     */
    protected $subtotal;

    /**
     * @var float
     */
    protected $total;

    /**
     * @var string
     */
    protected $billingAddress;

    /**
     * @var string
     */
    protected $shippingAddress;

    /**
     * @var OrderItem[]
     */
    protected $items = [];

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
     * @return string
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param string $billingAddress
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }

    /**
     * @return string
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * @param string $shippingAddress
     */
    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }

    /**
     * @return OrderItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param OrderItem[] $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @param OrderItem $orderItem
     */
    public function addItem(OrderItem $orderItem)
    {
        $this->items[] = $orderItem;
    }
}
