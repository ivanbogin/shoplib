<?php

namespace ShopLib\Cart\Service\Tests;

use ShopLib\Cart\Entity\Cart;
use ShopLib\Cart\Repository\CartRepositoryInterface;
use ShopLib\Cart\Service\CartService;
use ShopLib\Discount\Entity\Discount;
use ShopLib\Product\Entity\Product;
use ShopLib\Stock\Service\StockServiceInterface;

class CartServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var CartService
     */
    protected $cartService;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $cartRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $stockService;

    protected function setUp()
    {
        $this->cart = new Cart();
        $this->cartRepository = $this->getMock(CartRepositoryInterface::class);
        $this->stockService = $this->getMock(StockServiceInterface::class);
        $this->cartService = new CartService($this->cartRepository, $this->stockService);
    }

    public function testCartIsEmpty()
    {
        $this->assertCart(0, 0, 0);
    }

    public function testAddProductsToCart()
    {
        // Must check stock when adding products
        $this->stockService->expects($this->exactly(2))->method('getStock')->will($this->returnValue(2));

        $this->cartService->addProduct($this->cart, $this->createProduct('B00BGA9WK2', 399.95), 1);
        $this->cartService->addProduct($this->cart, $this->createProduct('B00FNQUN7Q', 54.95), 2);

        $this->assertCart(2, 3, 509.85);
    }

    public function testAddProductTwice()
    {
        $this->stockService->expects($this->exactly(2))->method('getStock')->will($this->returnValue(2));

        $this->cartService->addProduct($this->cart, $this->createProduct('B00FNQUN7Q', 54.95), 1);
        // Also price was changed
        $this->cartService->addProduct($this->cart, $this->createProduct('B00FNQUN7Q', 60), 1);
        $this->assertCart(1, 2, 120);
    }

    public function testUpdateItemQty()
    {
        // Must check stock when updating product quantity
        $this->stockService->expects($this->exactly(2))->method('getStock')->will($this->returnValue(3));

        $this->cartService->addProduct($this->cart, $this->createProduct('B00FNQUN7Q', 54.95), 1);
        $this->cartService->updateItemQty($this->cart, 'B00FNQUN7Q', 3);

        $this->assertCart(1, 3, 164.85);
    }

    public function testUpdateItemQtyToZero()
    {
        $this->stockService->expects($this->exactly(2))->method('getStock')->will($this->returnValue(2));

        $this->cartService->addProduct($this->cart, $this->createProduct('B00BGA9WK2', 399.95), 1);
        $this->cartService->addProduct($this->cart, $this->createProduct('B00FNQUN7Q', 54.95), 2);

        $this->cartService->updateItemQty($this->cart, 'B00BGA9WK2', 0);
        $this->assertCart(1, 2, 109.9);

        $this->cartService->updateItemQty($this->cart, 'B00FNQUN7Q', 0);
        $this->assertCart(0, 0, 0);
    }

    /**
     * @expectedException \ShopLib\Stock\Exception\OutOfStockException
     */
    public function testUpdateItemQtyOutOfStock()
    {
        $this->stockService->expects($this->exactly(2))->method('getStock')->will($this->returnValue(1));

        $this->cartService->addProduct($this->cart, $this->createProduct('B00FNQUN7Q', 54.95), 1);
        $this->cartService->updateItemQty($this->cart, 'B00FNQUN7Q', 3);
    }

    public function testRemoveItem()
    {
        $this->stockService->expects($this->exactly(1))->method('getStock')->will($this->returnValue(1));

        $this->cartService->addProduct($this->cart, $this->createProduct('B00FNQUN7Q', 54.95), 1);
        $this->assertCart(1, 1, 54.95);

        $this->cartService->removeItem($this->cart, 'B00FNQUN7Q');
        $this->assertCart(0, 0, 0);
    }

    public function testApplyDiscount()
    {
        $this->stockService->expects($this->exactly(1))->method('getStock')->will($this->returnValue(1));

        $this->cartService->addProduct($this->cart, $this->createProduct('B00BGA9WK2', 400), 1);
        $this->assertCart(1, 1, 400);

        $discount = new Discount();
        $discount->setType(Discount::TYPE_PRICE);
        $discount->setAmount(200);
        $this->cartService->applyDiscount($this->cart, $discount);

        $this->assertEquals(400, $this->cart->getSubtotal());
        $this->assertEquals(200, $this->cart->getTotal());
    }

    /**
     * Create fake product
     *
     * @param string     $sku
     * @param float      $originalPrice
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

    /**
     * Assert current cart, subtotal = total
     *
     * @param int   $count Number of positions in cart
     * @param int   $quantity Total number of items
     * @param float $total Total price
     */
    protected function assertCart($count, $quantity, $total)
    {
        $this->assertCount($count, $this->cart->getItems());
        $this->assertEquals($quantity, $this->cart->getQuantity());
        $this->assertEquals($total, $this->cart->getTotal());
        $this->assertEquals($total, $this->cart->getSubtotal());
    }

}
