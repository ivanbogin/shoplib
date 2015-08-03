<?php

namespace ShopLib\Cart\Service;

use ShopLib\Cart\Entity\Cart;
use ShopLib\Cart\Entity\CartItem;
use ShopLib\Cart\Repository\CartRepositoryInterface;
use ShopLib\Product\Entity\Product;
use ShopLib\Stock\Exception\OutOfStockException;
use ShopLib\Stock\Service\StockServiceInterface;

class CartService
{
    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var StockServiceInterface
     */
    protected $stockService;

    public function __construct(CartRepositoryInterface $cartRepository, StockServiceInterface $stockService)
    {
        $this->cartRepository = $cartRepository;
        $this->stockService = $stockService;
    }

    /**
     * Load cart from repository
     *
     * @param int $id
     *
     * @return Cart
     */
    public function getCartById($id)
    {
        return $this->cartRepository->getById($id);
    }

    /**
     * Save cart with items to repository
     *
     * @param Cart $cart
     */
    public function saveCart(Cart $cart)
    {
        $this->recalculate($cart);
        $this->cartRepository->save($cart);
    }

    /**
     * Add product to cart and reserve specified quantity in stock.
     * If there is not enough quantity in stock OutOfStockException will be thrown.
     * If product is already in the cart - then add additional quantity.
     *
     * @param Cart    $cart
     * @param Product $product
     * @param int     $qty
     *
     * @throws OutOfStockException
     */
    public function addProduct(Cart $cart, Product $product, $qty)
    {
        $this->stockService->reserve($product->getSku(), $qty);

        if ($cart->isItemInCart($product->getSku())) {
            $item = $cart->getItemBySku($product->getSku());
            $item->setProduct($product);
            $item->setQty($item->getQty() + $qty);
        } else {
            $item = new CartItem($product, $qty);
            $cart->addItem($item);
        }

        $this->saveCart($cart);
    }

    /**
     * Update item quantity.
     * Release items on stock before saving cart.
     * Reserve items on stock after saving cart.
     *
     * @param Cart   $cart
     * @param string $sku
     * @param int    $qty
     *
     * @throws \OutOfBoundsException
     * @throws OutOfStockException
     */
    public function updateItemQty(Cart $cart, $sku, $qty)
    {
        if (!$cart->isItemInCart($sku)) {
            throw new \OutOfBoundsException(sprintf('item %s not found in the cart', $sku));
        }
        if ($qty === 0) {
            $this->removeItem($cart, $sku);
        } else {
            $item = $cart->getItemBySku($sku);
            $diff = $qty - $item->getQty();

            if ($diff > 0) {
                $this->stockService->reserve($sku, $diff);
                $item->setQty($qty);
                $this->saveCart($cart);
            } else {
                $this->stockService->release($sku, abs($diff));
                $item->setQty($qty);
                $this->saveCart($cart);
            }
        }
    }

    /**
     * Remove item from the cart and release stock
     *
     * @param Cart   $cart
     * @param string $sku
     */
    public function removeItem(Cart $cart, $sku)
    {
        $qty = $cart->getItemBySku($sku)->getQty();
        $cart->removeItem($sku);
        $this->saveCart($cart);
        $this->stockService->release($sku, $qty);
    }

    /**
     * Recalculate cart total and quantity
     *
     * @param Cart $cart
     */
    protected function recalculate(Cart $cart)
    {
        $total = 0;
        $quantity = 0;
        foreach ($cart->getItems() as $item) {
            $total += $item->getTotal();
            $quantity += $item->getQty();
        }
        $cart->setTotal($total);
        $cart->setQuantity($quantity);
    }
}
