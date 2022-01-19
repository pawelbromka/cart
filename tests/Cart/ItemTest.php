<?php

declare(strict_types=1);

namespace Recruitment\Tests\Cart;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Recruitment\Cart\Exception\QuantityTooLowException;
use Recruitment\Cart\Item;
use Recruitment\Entity\Product;

class ItemTest extends TestCase
{
    /**
     * @test
     */
    public function itAcceptsConstructorArgumentsAndReturnsData(): void
    {
        $product = new Product();
        $product->setUnitPrice(10000);

        $item = new Item($product, 10);

        $this->assertEquals($product, $item->getProduct());
        $this->assertEquals(10, $item->getQuantity());
        $this->assertEquals(100000, $item->getTotalPrice());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function constructorThrowsExceptionWhenQuantityIsTooLow(): void
    {
        $product = new Product();
        $product->setMinimumQuantity(10);

        $this->expectException(InvalidArgumentException::class);
        new Item($product, 9);
    }

    /**
     * @test
     * @expectedException \Recruitment\Cart\Exception\QuantityTooLowException
     */
    public function itThrowsExceptionWhenSettingTooLowQuantity(): void
    {
        $product = new Product();
        $product->setMinimumQuantity(10);

        $item = new Item($product, 10);
        $this->expectException(QuantityTooLowException::class);
        $item->setQuantity(9);
    }
}
