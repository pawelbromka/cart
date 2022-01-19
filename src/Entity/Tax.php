<?php

declare(strict_types=1);

namespace Recruitment\Entity;

class Tax
{
    const TAX_RATE_0 = 0;
    const TAX_RATE_5 = 5;
    const TAX_RATE_8 = 8;
    const TAX_RATE_23 = 23;

    private $taxRate;

    public function __construct(int $taxRate = self::TAX_RATE_23)
    {
        $this->taxRate = $taxRate;
    }

    public function setTaxRate(int $taxRate): void
    {
        $this->taxRate = $taxRate;
    }

    public function getTaxRate(): int
    {
        return $this->taxRate;
    }

    public function getGrossPrice(int $price): int
    {
        $taxValue = ($price / 100) * $this->getTaxRate();

        return ($price + $taxValue);
    }
}
