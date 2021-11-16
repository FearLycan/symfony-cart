<?php

namespace App\Tests\Form\Type;

use App\Entity\Product;
use App\Form\Type\ProductType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class ProductTypeServiceTest extends TypeTestCase
{
    private $objectManager;

    protected function setUp(): void
    {
        // mock any dependencies
        $this->objectManager = $this->createMock(ObjectManager::class);

        parent::setUp();
    }

    protected function getExtensions()
    {
        // create a type instance with the mocked dependencies
        $type = new ProductType($this->objectManager);

        return [
            // register the type instances with the PreloadedExtension
            new PreloadedExtension([$type], []),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'Test Product Name',
            'price' => 33.33,
            'currency' => 'PLN',
        ];

        // Instead of creating a new instance, the one created in
        // getExtensions() will be used.
        $model = new Product();
        $form = $this->factory->create(ProductType::class, $model);

        $expected = new Product();
        $expected->setName($formData['name']);
        $expected->setPrice($formData['price']);
        $expected->setCurrency($formData['currency']);
        // ...populate $object properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $model was modified as expected when the form was submitted
        $this->assertEquals($expected, $model);
    }
}