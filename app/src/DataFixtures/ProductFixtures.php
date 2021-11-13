<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $productFixtures = [
            ['name' => 'The Godfather', 'price' => 59.99, 'currency' => 'PLN'],
            ['name' => 'Steve Jobs', 'price' => 49.95, 'currency' => 'PLN'],
            ['name' => 'The Return of Sherlock Holmes', 'price' => 39.99, 'currency' => 'PLN'],
            ['name' => 'The Little Prince', 'price' => 29.99, 'currency' => 'PLN'],
            ['name' => 'I Hate Myselfie!', 'price' => 19.99, 'currency' => 'PLN'],
            ['name' => 'The Trial', 'price' => 9.99, 'currency' => 'PLN'],
        ];

        foreach ($productFixtures as $fixture) {
            $product = new Product();

            $product->setName($fixture['name']);
            $product->setPrice($fixture['price']);
            $product->setCurrency($fixture['currency']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
