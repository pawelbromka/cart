<?php

declare(strict_types=1);

namespace Recruitment\Cart;

use OutOfBoundsException;
use Recruitment\Entity\Order;
use Recruitment\Entity\Product;

class Cart
{
    /**
     * @var array $items
     */
    private $items = [];

    /**
     * @param Product $product
     * @param int $quantity
     * @throws Exception\QuantityTooLowException
     */
    public function addProduct(Product $product, int $quantity = 1): void
    {
        $cartItemIdForProduct = $this->getCartItemIdForProduct($product);

        if ($cartItemIdForProduct !== null) {
            /** @var Item $item */
            $item = $this->items[$cartItemIdForProduct];
            $item->setQuantity($item->getQuantity() + $quantity);
        } else {
            $item = new Item($product, $quantity);
            $this->items[] = $item;
        }
    }

    /**
     * @param Product $product
     * @param int $quantity
     * @throws Exception\QuantityTooLowException
     */
    public function setQuantity(Product $product, int $quantity): void
    {
        $cartItemIdForProduct = $this->getCartItemIdForProduct($product);

        if ($cartItemIdForProduct !== null) {
            /** @var Item $item */
            $item = $this->items[$cartItemIdForProduct];
            $item->setQuantity($quantity);
        } else {
            $item = new Item($product, $quantity);
            $this->items[] = $item;
        }
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getTotalPrice(): int
    {
        $totalPrice = 0;

        /** @var Item $item */
        foreach ($this->items as $item) {
            $totalPrice += $item->getTotalPrice();
        }

        return $totalPrice;
    }

    /**
     * @return int
     */
    public function getTotalPriceGross(): int
    {
        $totalPriceGross = 0;

        /** @var Item $item */
        foreach ($this->items as $item) {
            $totalPriceGross += $item->getTotalPriceGross();
        }

        return $totalPriceGross;
    }

    /**
     * @param int $key
     * @return Item
     * @throws OutOfBoundsException
     */
    public function getItem(int $key): Item
    {
        if (!isset($this->items[$key])) {
            throw new OutOfBoundsException();
        }

        return $this->items[$key];
    }

    /**
     * @param Product $product
     */
    public function removeProduct(Product $product): void
    {
        $cartItemIdForProduct = $this->getCartItemIdForProduct($product);

        if ($cartItemIdForProduct !== null) {
            unset($this->items[$cartItemIdForProduct]);
            $this->items = array_values($this->items);
        }
    }

    /**
     *
     */
    public function clearCart(): void
    {
        $this->items = [];
    }

    /**
     * @param int $orderId
     * @return Order
     */
    public function checkout(int $orderId): Order
    {
        $order = new Order();
        $order->setId($orderId);
        $order->setItems($this->getItems());
        $order->setTotalPrice($this->getTotalPrice());
        $order->setTotalPriceGross($this->getTotalPriceGross());

        $this->clearCart();

        return $order;
    }

    /**
     * @param Product $product
     * @return int|null
     */
    private function getCartItemIdForProduct(Product $product)
    {
        /** @var Item $item */
        foreach ($this->items as $cartItemId => $item) {
            if ($product->getId() == $item->getProduct()->getId()) {
                return $cartItemId;
            }
        }

        return null;
    }
}
