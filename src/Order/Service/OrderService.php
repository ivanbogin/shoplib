<?php

namespace ShopLib\Order\Service;

use ShopLib\Cart\Entity\Cart;
use ShopLib\Order\Entity\Checkout;
use ShopLib\Order\Repository\OrderRepositoryInterface;
use ShopLib\Stock\Exception\OutOfStockException;
use ShopLib\Stock\Service\StockServiceInterface;

class OrderService
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var StockServiceInterface
     */
    protected $stockService;

    public function __construct(OrderRepositoryInterface $orderRepository, StockServiceInterface $stockService)
    {
        $this->orderRepository = $orderRepository;
        $this->stockService = $stockService;
    }

    /**
     * Create Order based on Checkout info.
     * Reserve stock for ordered products.
     * Throw OutOfStockException if any Product in Cart is not available in Stock.
     *
     * @param Cart     $cart
     * @param Checkout $checkout
     *
     * @throws OutOfStockException
     */
    public function checkoutCart(Cart $cart, Checkout $checkout)
    {
        foreach ($cart->getItems() as $item) {
            // TODO reserve stock and release if exception will be thrown
        }
    }
}
