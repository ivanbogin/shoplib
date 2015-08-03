<?php

namespace ShopLib\Order\Repository;

use ShopLib\Order\Entity\Order;

interface OrderRepositoryInterface
{
    /**
     * Get Order by unique ID
     *
     * @param int $id
     * @return Order
     */
    public function getById($id);

    /**
     * Save Order to repository and return Order ID
     *
     * @param Order $order
     * @return int
     */
    public function save(Order $order);
}
