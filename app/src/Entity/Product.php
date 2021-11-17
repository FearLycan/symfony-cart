<?php

namespace App\Entity;

use App\Common\Entity\Entity;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(indexes={
 *     @ORM\Index(name="product_name_index", columns={"name"})
 * })
 */
class Product extends Entity
{
    const CURRENCIES = ['PLN', 'USD', 'EUR'];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="name", nullable=false)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="float", precision=5, scale=2, name="price", nullable=false)
     * @Assert\Type(type="float", message="Price must be a numeric value")
     * @Assert\NotBlank
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=3, name="currency",nullable=false)
     * @Assert\Choice(choices=Product::CURRENCIES, message="Choose a valid currency.")
     * @Assert\NotBlank
     */
    private $currency;

    /**
     * @ORM\ManyToMany(targetEntity=Cart::class, mappedBy="products")
     * @Ignore
     */
    private $carts;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return Collection|Cart[]
     * @Ignore
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->addProduct($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->removeElement($cart)) {
            $cart->removeProduct($this);
        }

        return $this;
    }
}
