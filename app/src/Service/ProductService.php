<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductService
{
    private ProductRepository $repository;
    private EntityManagerInterface $em;
    private ValidatorInterface $validator;

    /**
     * ProductService constructor.
     * @param ProductRepository $repository
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     */
    public function __construct(ProductRepository $repository, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->validator = $validator;
    }

    /**
     * @param $productData
     * @return Product|string
     */
    public function createProduct($productData)
    {
        $name = $productData['name'];
        $currency = $productData['currency'];
        $price = $productData['price'];

        $product = new Product();
        $product->setName($name);
        $product->setCurrency($currency);
        $product->setPrice($price);

        $errors = $this->validator->validate($product);

        if (count((array)$errors) > 0) {
            return (string)$errors;
        }

        try {
            $this->em->persist($product);
            $this->em->flush();
            return $product;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}