<?php

declare(strict_types=1);

namespace Recruitment\Cart;

use InvalidArgumentException;
use Recruitment\Cart\Exception\QuantityTooLowException;
use Recruitment\Entity\Product;

class Item
{
    /**
     * @var Product $product
     */
    private $product;

    /**
     * @var int $quantity
     */
    private $quantity;

    public function __construct(Product $product, int $quantity)
    {
        if ($quantity < $product->getMinimumQuantity()) {
            throw new InvalidArgumentException();
        }

        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param int $quantity
     * @throws QuantityTooLowException
     */
    public function setQuantity(int $quantity): void
    {
        if ($quantity < $this->product->getMinimumQuantity()) {
            throw new QuantityTooLowException();
        }

        $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return int
     */
    public function getTotalPrice(): int
    {
        return $this->product->getUnitPrice() * $this->quantity;
    }

    /**
     * @return int
     */
    public function getTotalPriceGross(): int
    {
        return $this->getProduct()->getTax()->getGrossPrice($this->getTotalPrice());
    }
}
