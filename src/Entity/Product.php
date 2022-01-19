<?php

declare(strict_types=1);

namespace Recruitment\Entity;

use InvalidArgumentException;
use Recruitment\Entity\Exception\InvalidUnitPriceException;

class Product
{
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var int $price
     */
    private $price;

    /**
     * @var int $minimumQuantity
     */
    private $minimumQuantity = 1;

    /**
     * @var Tax $tax;
     */
    private $tax;

    public function __construct()
    {
        $this->tax = new Tax();
    }

    /**
     * @param int $price
     * @throws InvalidUnitPriceException
     */
    public function setUnitPrice(int $price): void
    {
        if ($price <= 0) {
            throw new InvalidUnitPriceException();
        }

        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getUnitPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $minimumQuantity
     */
    public function setMinimumQuantity(int $minimumQuantity): void
    {
        if ($minimumQuantity < 1) {
            throw new InvalidArgumentException();
        }

        $this->minimumQuantity = $minimumQuantity;
    }

    /**
     * @return int
     */
    public function getMinimumQuantity(): int
    {
        return $this->minimumQuantity;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Tax
     */
    public function getTax(): Tax
    {
        return $this->tax;
    }

    /**
     * @param int $taxRate
     */
    public function setTaxRate(int $taxRate): void
    {
        $this->tax->setTaxRate($taxRate);
    }

    /**
     * @return int
     */
    public function getTaxRate(): int
    {
        return $this->tax->getTaxRate();
    }
}
