<?php

namespace App\Entity;

use App\Common\Entity\Entity;
use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;

/**
 * @ORM\Entity(repositoryClass=CartRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Cart extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UlidGenerator::class)
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, inversedBy="carts")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function getTotalProducts(): int
    {
        return $this->products->count();
    }

    public function getTotalPrice(): float
    {
        $price = 0;
        foreach ($this->getProducts() as $product) {
            $price += $product->getPrice();
        }

        return $price;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }
}
