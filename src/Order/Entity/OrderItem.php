<?php

namespace ShopLib\Order\Entity;

class OrderItem
{
    /**
     * @var int|string
     */
    protected $id;
    /**
     * @var int|string
     */
    protected $orderId;
    /**
     * @var string
     */
    protected $sku;
    /**
     * @var int
     */
    protected $qty;
    /**
     * @var float
     */
    protected $itemPrice;

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int|string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param int|string $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
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
    public function getItemPrice()
    {
        return $this->itemPrice;
    }

    /**
     * @param float $itemPrice
     */
    public function setItemPrice($itemPrice)
    {
        $this->itemPrice = $itemPrice;
    }
}
