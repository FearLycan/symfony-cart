<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductServiceTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ProductRepository
     */
    private $repository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->repository = $this->entityManager->getRepository(Product::class);
    }

    public function testCreateProduct(): void
    {
        $productData = [
            'name' => 'Test Product Name',
            'price' => 33.33,
            'currency' => 'USD',
            'created_at' => new \DateTime('now')
        ];

        $validator = $this->createMock(ValidatorInterface::class);

        $productService = new ProductService($this->repository, $this->entityManager, $validator);

        $productFromMethod = $productService->createProduct($productData);

        $this->assertSame($productData['name'], $productFromMethod->getName());
        $this->assertSame($productData['price'], $productFromMethod->getPrice());
        $this->assertSame($productData['currency'], $productFromMethod->getCurrency());
        //$this->assertSame($productData['created_at'], $productFromMethod->getCreatedAt());

        $productFromDB = $this->repository->findOneBy(['name' => $productData['name']]);

        $this->assertSame(
            $productFromDB->setCreatedAt($productData['created_at']),
            $productFromMethod->setCreatedAt($productData['created_at'])
        );
    }
}