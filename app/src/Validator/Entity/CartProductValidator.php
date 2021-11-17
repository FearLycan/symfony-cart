<?php


namespace App\Validator\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

class CartProductValidator
{
    /**
     * @ORM\ManyToMany(
     *  targetEntity="App\Entity\Product",
     *  mappedBy="carts",
     * )
     * @Assert\Valid
     */
    protected $products;
}