<?php

declare(strict_types=1);

namespace Recruitment\Entity;

use Recruitment\Cart\Item;

class Order
{
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var array $items
     */
    private $items = [];

    /**
     * @var int $totalPrice
     */
    private $totalPrice;

    /**
     * @var int $totalPriceGross
     */
    private $totalPriceGross;

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param int $totalPrice
     */
    public function setTotalPrice(int $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @return int
     */
    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }

    /**
     * @param int $totalPriceGross
     */
    public function setTotalPriceGross(int $totalPriceGross): void
    {
        $this->totalPriceGross = $totalPriceGross;
    }

    /**
     * @return int
     */
    public function getTotalPriceGross(): int
    {
        return $this->totalPriceGross;
    }

    /**
     * @return array
     */
    public function getDataForView(): array
    {
        $results = [];

        $results['id'] = $this->getId();

        /** @var Item $item */
        foreach ($this->getItems() as $item) {
            $results['items'][] = [
                'id' => $item->getProduct()->getId(),
                'quantity' => $item->getQuantity(),
                'tax_rate' => $item->getProduct()->getTaxRate() . '%',
                'total_price' => $item->getTotalPrice(),
                'total_price_gross' => $item->getTotalPriceGross(),
            ];
        }

        $results['total_price'] = $this->getTotalPrice();
        $results['total_price_gross'] = $this->getTotalPriceGross();

        return $results;
    }
}
