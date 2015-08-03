<?php

namespace ShopLib\Order\Service\Tests;

use ShopLib\Cart\Entity\Cart;
use ShopLib\Cart\Repository\CartRepositoryInterface;
use ShopLib\Cart\Service\CartService;
use ShopLib\Order\Entity\Address;
use ShopLib\Order\Entity\Checkout;
use ShopLib\Order\Repository\OrderRepositoryInterface;
use ShopLib\Order\Service\OrderService;
use ShopLib\Product\Entity\Product;
use ShopLib\Stock\Service\StockServiceInterface;

class OrderServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $cartRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $orderRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $stockService;

    /**
     * @var OrderService
     */
    protected $orderService;

    /**
     * @var CartService
     */
    protected $cartService;

    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var Checkout
     */
    protected $checkout;

    protected function setUp()
    {
        $this->cart = new Cart();
        $this->cartRepository = $this->getMock(CartRepositoryInterface::class);
        $this->orderRepository = $this->getMock(OrderRepositoryInterface::class);
        $this->stockService = $this->getMock(StockServiceInterface::class);
        $this->orderService = new OrderService($this->orderRepository, $this->stockService);
        $this->cartService = new CartService($this->cartRepository, $this->stockService);

        $shippingAddress = new Address();
        $shippingAddress->setName('Homer Jay Simpson');
        $shippingAddress->setAddress('742 Evergreen Terrace');
        $shippingAddress->setCity('Springfield');
        $shippingAddress->setCountry('United States');

        $billingAddress = new Address();
        $billingAddress->setName('Charles Montgomery Burns');
        $billingAddress->setAddress('100 Industrial Way');
        $billingAddress->setCity('Springfield');
        $billingAddress->setCountry('United States');

        $this->checkout = new Checkout();
        $this->checkout->setShippingAddress($shippingAddress);
        $this->checkout->setBillingAddress($billingAddress);
    }

    public function testCreateOrderFromCart()
    {
        $this->markTestSkipped();

        $this->cartService->addProduct($this->cart, $this->createProduct('B00BGA9WK2', 399.95), 1);

        $this->orderService->createOrderFromCart($this->cart, $this->checkout);
    }

    /**
     * Create fake product
     *
     * @param string $sku
     * @param float $originalPrice
     * @param float|null $specialPrice
     * @return Product
     */
    protected function createProduct($sku, $originalPrice, $specialPrice = null)
    {
        $product = new Product();
        $product->setSku($sku);
        $product->setOriginalPrice($originalPrice);
        $product->setSpecialPrice($specialPrice);
        return $product;
    }
}
