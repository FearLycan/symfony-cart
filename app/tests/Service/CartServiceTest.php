<?php

namespace App\Tests\Service;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CartServiceTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var CartRepository
     */
    private $cartRepository;

    private $cart;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->productRepository = $this->entityManager->getRepository(Product::class);
        $this->cartRepository = $this->entityManager->getRepository(Cart::class);
    }

    public function testCreate(): void
    {
        $productData = [
            'product_id' => 1,
            'created_at' => new \DateTime('now')
        ];

        $productService = new CartService($this->productRepository, $this->entityManager, $this->cartRepository);
        $this->cart = $this->createCart($productService, $productData);

        $cartFromDB = $this->cartRepository->findOneBy(['id' => $this->cart->getId()]);

        $this->assertSame(
            $cartFromDB->setCreatedAt($productData['created_at']),
            $this->cart->setCreatedAt($productData['created_at'])
        );
    }

    public function testAdd(): void
    {
        $productData1 = [
            'product_id' => 1,
            'created_at' => new \DateTime('now')
        ];

        $productData2 = [
            'product_id' => 2,
        ];

        $productService = new CartService($this->productRepository, $this->entityManager, $this->cartRepository);

        $this->cart = $this->createCart($productService, $productData1);

        $this->cart = $productService->add($this->cart, $productData2);
        $cartFromDB = $this->cartRepository->findOneBy(['id' => $this->cart->getId()]);

        $this->assertSame(
            $cartFromDB->setCreatedAt($productData1['created_at']),
            $this->cart->setCreatedAt($productData1['created_at'])
        );
    }

    public function testRemove(): void
    {
        $productData1 = [
            'product_id' => 1,
            'created_at' => new \DateTime('now')
        ];

        $productService = new CartService($this->productRepository, $this->entityManager, $this->cartRepository);

        $this->cart = $this->createCart($productService, $productData1);

        $this->cart = $productService->remove($this->cart, $productData1);
        $cartFromDB = $this->cartRepository->findOneBy(['id' => $this->cart->getId()]);

        $this->assertSame(
            $cartFromDB->setCreatedAt($productData1['created_at']),
            $this->cart->setCreatedAt($productData1['created_at'])
        );
    }

    public function testSummary()
    {
        $productData = [
            'product_id' => 1,
            'created_at' => new \DateTime('now')
        ];

        $cartService = new CartService($this->productRepository, $this->entityManager, $this->cartRepository);

        $this->cart = $this->createCart($cartService, $productData);
        $this->entityManager->refresh($this->cart);

        $cartFromDB = $this->cartRepository->findOneBy(['id' => $this->cart->getId()]);

        $this->assertEquals(
            $cartService->summary($cartFromDB)['summary'],
            $cartService->summary($this->cart)['summary'],
        );
    }

    private function createCart($productService, $productData)
    {
        return $productService->create(new Cart(), $productData);
    }
}