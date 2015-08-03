<?php

namespace ShopLib\Order\Service;

use ShopLib\Cart\Entity\Cart;
use ShopLib\Order\Entity\Checkout;
use ShopLib\Order\Entity\Order;
use ShopLib\Order\Entity\OrderItem;
use ShopLib\Order\Repository\OrderRepositoryInterface;
use ShopLib\Product\Entity\Product;
use ShopLib\Stock\Exception\OutOfStockException;
use ShopLib\Stock\Service\StockServiceInterface;

/**
 * Order manipulation service
 */
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

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param StockServiceInterface    $stockService
     */
    public function __construct(OrderRepositoryInterface $orderRepository, StockServiceInterface $stockService)
    {
        $this->orderRepository = $orderRepository;
        $this->stockService = $stockService;
    }

    /**
     * Create Order based on Checkout info.
     * Reserve stock for ordered products.
     * Throw OutOfStockException if any Product in Cart is not available in the Stock.
     *
     * @param Cart     $cart
     * @param Checkout $checkout
     * @return Order
     * @throws OutOfStockException
     */
    public function createOrderFromCart(Cart $cart, Checkout $checkout)
    {
        $order = new Order();
        $order->setBillingAddress($checkout->getBillingAddress());
        $order->setShippingAddress($checkout->getShippingAddress());
        $order->setSubtotal($cart->getSubtotal());
        $order->setTotal($cart->getTotal());

        foreach ($cart->getItems() as $cartItem) {
            $this->addProductToOrder($order, $cartItem->getProduct(), $cartItem->getQty());
        }

        $this->orderRepository->save($order);
        return $order;
    }

    /**
     * Add Product to Order and reserve stock
     *
     * @param Order   $order
     * @param Product $product
     * @param int     $qty
     * @throws OutOfStockException
     */
    public function addProductToOrder(Order $order, Product $product, $qty)
    {
        try {
            $this->stockService->reserve($product->getSku(), $qty);

            $orderItem = new OrderItem();
            $orderItem->setSku($product->getSku());
            $orderItem->setQty($qty);
            $orderItem->setItemPrice($product->getPrice());

            $order->addItem($orderItem);
        } catch (OutOfStockException $e) {
            // Return all reserved items back
            $this->releaseOrderProducts($order);
            throw new OutOfStockException();
        }
    }

    /**
     * Return all reserved items back to the stock
     *
     * @param Order $order
     */
    protected function releaseOrderProducts(Order $order)
    {
        foreach ($order->getItems() as $orderItem) {
            $this->stockService->release($orderItem->getSku(), $orderItem->getQty());
        }
    }
}
