<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CartService
{
    private ProductRepository $productRepository;
    private CartRepository $cartRepository;
    private EntityManagerInterface $em;

    public const MAX_ITEMS = 3;

    /**
     * ProductService constructor.
     * @param ProductRepository $productRepository
     * @param CartRepository $cartRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(ProductRepository $productRepository, EntityManagerInterface $em,
                                CartRepository $cartRepository)
    {
        $this->productRepository = $productRepository;
        $this->cartRepository = $cartRepository;
        $this->em = $em;
    }

    public function create(Cart $cart)
    {
        $this->em->persist($cart);
        $this->em->flush();

        return $cart;
    }

    public function add(Cart $cart, Product $product)
    {
        if (count($cart->getProducts()) >= self::MAX_ITEMS) {
            throw new Exception('The cart is full, the number of permitted products is: ' . self::MAX_ITEMS, Response::HTTP_INSUFFICIENT_STORAGE);
        }

        foreach ($cart->getProducts() as $item) {
            if ($item->getId() == $product->getId()) {
                throw new Exception('The product ID: ' . $product->getId() . ' is already in the car.', Response::HTTP_INSUFFICIENT_STORAGE);
            }
        }

        $cart->addProduct($product);

        $this->em->persist($cart);
        $this->em->flush();
    }

    public function remove(Cart $cart, Product $product)
    {
        $deleted = false;

        foreach ($cart->getProducts() as $item) {
            if ($item->getId() == $product->getId()) {
                $cart->removeProduct($item);
                $this->em->persist($cart);
                $this->em->flush();

                $deleted = true;
            }
        }

        if (!$deleted) {
            throw new Exception('No product ID: ' . $product->getId() . ' in the cart.', Response::HTTP_INSUFFICIENT_STORAGE);
        }
    }

    public function summary(Cart $cart)
    {
        return [
            'cart_id' => $cart->getId(),
            'items' => $cart->getProducts(),
            'summary' => [
                'items' => $cart->getTotalProducts(),
                'total_price' => $cart->getTotalPrice(),
            ],
        ];
    }
}