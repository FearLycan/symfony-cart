<?php


namespace App\Tests\Form\Type;


use App\Entity\Cart;
use App\Entity\Product;
use App\Form\Type\CartType;
use Symfony\Component\Form\Test\TypeTestCase;

class CartTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'products' => [1],
        ];

        $model = new Cart();
        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(CartType::class, $model);

        $expected = new Cart();
        // ...populate $object properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $model was modified as expected when the form was submitted
        $this->assertEquals($expected, $model);
    }
}