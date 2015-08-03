<?php

namespace ShopLib\Product\Entity;

class Product
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $typeId;

    /**
     * @var string
     */
    protected $sku;

    /**
     * @var float
     */
    protected $originalPrice;

    /**
     * @var float
     */
    protected $specialPrice;

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
     * @return int
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * @param int $typeId
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
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
     * @return float
     */
    public function getOriginalPrice()
    {
        return $this->originalPrice;
    }

    /**
     * @param float $originalPrice
     */
    public function setOriginalPrice($originalPrice)
    {
        $this->originalPrice = $originalPrice;
    }

    /**
     * @return float
     */
    public function getSpecialPrice()
    {
        return $this->specialPrice;
    }

    /**
     * @param float $specialPrice
     */
    public function setSpecialPrice($specialPrice)
    {
        $this->specialPrice = $specialPrice;
    }

    /**
     * Get special price if it's not empty or original price otherwise.
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->getSpecialPrice() ?: $this->getOriginalPrice();
    }
}
