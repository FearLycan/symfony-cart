<?php

namespace App\Tests\Repository;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManager;

class ProductRepositoryTest extends KernelTestCase
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

    public function testGetByPage()
    {
        $cases = [
            ['page' => 1, 'products' => 3],
            ['page' => 2, 'products' => 3],
        ];

        foreach ($cases as $case) {
            $products = $this->repository->getByPage($case['page']);

            $this->assertTrue(count($products) == $case['products']);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}