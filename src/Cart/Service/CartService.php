<?php

namespace ShopLib\Cart\Service;

use ShopLib\Cart\Entity\Cart;
use ShopLib\Cart\Entity\CartItem;
use ShopLib\Cart\Repository\CartRepositoryInterface;
use ShopLib\Product\Entity\Product;
use ShopLib\Stock\Exception\OutOfStockException;
use ShopLib\Stock\Service\StockServiceInterface;

/**
 * Cart manipulation service
 */
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

    /**
     * @param CartRepositoryInterface $cartRepository
     * @param StockServiceInterface   $stockService
     */
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
     * Recalculate and save Cart with items to repository
     *
     * @param Cart $cart
     */
    public function saveCart(Cart $cart)
    {
        $this->recalculate($cart);
        $this->cartRepository->save($cart);
    }

    /**
     * Add product to cart and check for specified quantity in stock.
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
        if ($cart->isItemInCart($product->getSku())) {
            $item = $cart->getItemBySku($product->getSku());
            $item->setProduct($product);
            $item->setQty($item->getQty() + $qty);
        } else {
            $item = new CartItem($product, $qty);
            $cart->addItem($item);
        }

        $stockQty = $this->stockService->getStock($product->getSku());
        if ($item->getQty() > $stockQty) {
            throw new OutOfStockException();
        }

        $this->saveCart($cart);
    }

    /**
     * Update item quantity.
     * If quantity <= 0 - remove from cart.
     * If there is no such quantity in the stock - throw OutOfStockException.
     * If there is no such product in the cart - throw OutOfBoundsException.
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

        if ($qty <= 0) {
            $this->removeItem($cart, $sku);
            return;
        }

        if ($qty > $this->stockService->getStock($sku)) {
            throw new OutOfStockException();
        }

        $item = $cart->getItemBySku($sku);
        $item->setQty($qty);
        $this->saveCart($cart);
    }

    /**
     * Remove item from the cart
     *
     * @param Cart   $cart
     * @param string $sku
     */
    public function removeItem(Cart $cart, $sku)
    {
        $cart->removeItem($sku);
        $this->saveCart($cart);
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
        $cart->setSubtotal($total);
        $cart->setTotal($total);
        $cart->setQuantity($quantity);
    }
}
