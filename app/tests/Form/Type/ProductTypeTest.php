<?php

namespace App\Tests\Form\Type;

use App\Entity\Product;
use App\Form\Type\ProductType;
use Symfony\Component\Form\Test\TypeTestCase;

class ProductTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'Test Product Name',
            'price' => 33.33,
            'currency' => 'PLN',
        ];

        $model = new Product();
        // $model will retrieve data from the form submission; pass it as the second argument
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