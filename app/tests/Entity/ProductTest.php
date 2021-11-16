<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    const PRODUCT_NAME = 'Test Product Name';
    const PRODUCT_PRICE = 33.33;
    const PRODUCT_CURRENCY = 'PLN';

    public function testProductCreate()
    {
        $product = new Product();

        $this->assertInstanceOf(Product::class, $product);

        $product->setName(self::PRODUCT_NAME);
        $product->setPrice(self::PRODUCT_PRICE);
        $product->setCurrency(self::PRODUCT_CURRENCY);

        $now = new \DateTime('now');
        $product->setCreatedAt($now);

        $this->assertEquals(self::PRODUCT_NAME, $product->getName());
        $this->assertEquals(self::PRODUCT_PRICE, $product->getPrice());
        $this->assertEquals(self::PRODUCT_CURRENCY, $product->getCurrency());
        $this->assertEquals($now, $product->getCreatedAt());
    }
}