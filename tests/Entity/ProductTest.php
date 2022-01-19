<?php

declare(strict_types=1);

namespace Recruitment\Tests\Entity;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Recruitment\Entity\Exception\InvalidUnitPriceException;
use Recruitment\Entity\Product;

class ProductTest extends TestCase
{
    /**
     * @test
     * @expectedException \Recruitment\Entity\Exception\InvalidUnitPriceException
     */
    public function itThrowsExceptionForInvalidUnitPrice(): void
    {
        $product = new Product();
        $this->expectException(InvalidUnitPriceException::class);
        $product->setUnitPrice(0);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function itThrowsExceptionForInvalidMinimumQuantity(): void
    {
        $product = new Product();
        $this->expectException(InvalidArgumentException::class);
        $product->setMinimumQuantity(0);
    }
}
