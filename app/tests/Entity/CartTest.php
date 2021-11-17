<?php


namespace App\Tests\Entity;


use App\Entity\Cart;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testProductCreate()
    {
        $cart = new Cart();

        $this->assertInstanceOf(Cart::class, $cart);

        $product = new Product();
        $product->setName(ProductTest::PRODUCT_NAME);
        $product->setPrice(ProductTest::PRODUCT_PRICE);
        $product->setCurrency(ProductTest::PRODUCT_CURRENCY);

        $now = new \DateTime('now');
        $product->setCreatedAt($now);

        $this->assertEquals(ProductTest::PRODUCT_NAME, $product->getName());
        $this->assertEquals(ProductTest::PRODUCT_PRICE, $product->getPrice());
        $this->assertEquals(ProductTest::PRODUCT_CURRENCY, $product->getCurrency());
        $this->assertEquals($now, $product->getCreatedAt());
        $this->assertEquals(null, $cart->getId());

        $cart->addProduct($product);
        $this->assertInstanceOf(Product::class, $cart->getProducts()[0]);
        $this->assertTrue($cart->getTotalPrice() === ProductTest::PRODUCT_PRICE);
        $this->assertTrue($cart->getTotalProducts() === 1);

        $cart->removeProduct($product);
        $this->assertEmpty($cart->getProducts());
    }
}