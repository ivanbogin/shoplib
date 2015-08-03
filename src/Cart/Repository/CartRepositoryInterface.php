<?php

namespace ShopLib\Cart\Repository;

use ShopLib\Cart\Entity\Cart;

interface CartRepositoryInterface
{
    /**
     * Get cart by unique id
     *
     * @param int $id
     * @return Cart
     */
    public function getById($id);

    /**
     * @param Cart $cart
     */
    public function save(Cart $cart);
}
