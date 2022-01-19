<?php

declare(strict_types=1);

namespace Recruitment\Tests\Cart;

use OutOfBoundsException;
use PHPUnit\Framework\TestCase;
use Recruitment\Cart\Cart;
use Recruitment\Entity\Order;
use Recruitment\Entity\Product;
use Recruitment\Entity\Tax;

class CartTest extends TestCase
{
    /**
     * @test
     */
    public function itAddsOneProduct(): void
    {
        $product = $this->buildTestProduct(1, 15000);

        $cart = new Cart();
        $cart->addProduct($product, 1);

        $this->assertCount(1, $cart->getItems());
        $this->assertEquals(15000, $cart->getTotalPrice());
        $this->assertEquals($product, $cart->getItem(0)->getProduct());
    }

    /**
     * @test
     */
    public function itRemovesExistingProduct(): void
    {
        $product1 = $this->buildTestProduct(1, 15000);
        $product2 = $this->buildTestProduct(2, 10000);

        $cart = new Cart();
        $cart->addProduct($product1, 1);
        $cart->addProduct($product2, 1);
        $cart->removeProduct($product1);

        $this->assertCount(1, $cart->getItems());
        $this->assertEquals(10000, $cart->getTotalPrice());
        $this->assertEquals($product2, $cart->getItem(0)->getProduct());
    }

    /**
     * @test
     */
    public function itIncreasesQuantityWhenAddingAnExistingProduct(): void
    {
        $product = $this->buildTestProduct(1, 15000);

        $cart = new Cart();
        $cart->addProduct($product, 1);
        $cart->addProduct($product, 2);

        $this->assertCount(1, $cart->getItems());
        $this->assertEquals(45000, $cart->getTotalPrice());
    }

    /**
     * @test
     */
    public function itUpdatesQuantityOfAnExistingItem(): void
    {
        $product = $this->buildTestProduct(1, 15000);

        $cart = new Cart();
        $cart->addProduct($product, 1);
        $cart->setQuantity($product, 2);

        $this->assertEquals(30000, $cart->getTotalPrice());
        $this->assertEquals(2, $cart->getItem(0)->getQuantity());
    }

    /**
     * @test
     */
    public function itAddsANewItemWhileSettingQuantityForNonExistentItem(): void
    {
        $product = $this->buildTestProduct(1, 15000);

        $cart = new Cart();
        $cart->setQuantity($product, 1);

        $this->assertEquals(15000, $cart->getTotalPrice());
        $this->assertCount(1, $cart->getItems());
    }

    /**
     * @test
     * @dataProvider getNonExistentItemIndexes
     * @expectedException \OutOfBoundsException
     */
    public function itThrowsExceptionWhileGettingNonExistentItem(int $index): void
    {
        $product = $this->buildTestProduct(1, 15000);

        $cart = new Cart();
        $cart->addProduct($product, 1);
        $this->expectException(OutOfBoundsException::class);
        $cart->getItem($index);
    }

    /**
     * @test
     */
    public function removingNonExistentItemDoesNotRaiseException(): void
    {
        $cart = new Cart();
        $cart->addProduct($this->buildTestProduct(1, 15000));
        $cart->removeProduct(new Product());

        $this->assertCount(1, $cart->getItems());
    }

    /**
     * @test
     */
    public function itClearsCartAfterCheckout(): void
    {
        $cart = new Cart();
        $cart->addProduct($this->buildTestProduct(1, 15000));
        $cart->addProduct($this->buildTestProduct(2, 10000), 2);

        $order = $cart->checkout(7);

        $this->assertCount(0, $cart->getItems());
        $this->assertEquals(0, $cart->getTotalPrice());
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(['id' => 7, 'items' => [
            ['id' => 1, 'quantity' => 1, 'tax_rate' => '23%', 'total_price' => 15000, 'total_price_gross' => 18450],
            ['id' => 2, 'quantity' => 2, 'tax_rate' => '23%', 'total_price' => 20000, 'total_price_gross' => 24600],
        ], 'total_price' => 35000, 'total_price_gross' => 43050], $order->getDataForView());
    }

    /**
     * @test
     * @dataProvider getGrossPricesForTaxRates
     */
    public function itCalculatesGrossPriceForTaxRates(int $taxRate, int $grossPrice): void
    {
        $product = $this->buildTestProduct(1, 10000);
        $product->setTaxRate($taxRate);

        $cart = new Cart();
        $cart->addProduct($product, 1);

        $this->assertEquals($grossPrice, $cart->getTotalPriceGross());
    }

    public function getNonExistentItemIndexes(): array
    {
        return [
            [PHP_INT_MIN],
            [-1],
            [1],
            [PHP_INT_MAX],
        ];
    }

    public function getGrossPricesForTaxRates(): array
    {
        return [
            [Tax::TAX_RATE_23, 12300],
            [Tax::TAX_RATE_8, 10800],
            [Tax::TAX_RATE_5, 10500],
            [Tax::TAX_RATE_0, 10000],
        ];
    }

    private function buildTestProduct(int $id, int $price): Product
    {
        $product = new Product();
        $product->setId($id);
        $product->setUnitPrice($price);

        return $product;
    }
}
