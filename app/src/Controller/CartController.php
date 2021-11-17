<?php

namespace App\Controller;

use App\Common\Controller\ApiController;
use App\Entity\Cart;
use App\Form\Type\CartType;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Service\CartService;
use App\Service\ErrorHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CartController
 * @package App\Controller
 * @Route("/api", name="cart_")
 */
class CartController extends ApiController
{
    private EntityManagerInterface $em;
    private CartRepository $repository;
    private ProductRepository $productRepository;
    private ErrorHandlerInterface $errorHandler;
    private CartService $service;

    public function __construct(EntityManagerInterface $em, CartRepository $cartRepository,
                                ErrorHandlerInterface $errorHandler, ProductRepository $productRepository, CartService $cartService)
    {
        $this->em = $em;
        $this->repository = $cartRepository;
        $this->errorHandler = $errorHandler;
        $this->productRepository = $productRepository;
        $this->service = $cartService;
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/cart/{key}", methods={"GET"}, name="show")
     */
    public function show($key)
    {
        $cart = $this->find($key);
        return $this->respond($this->service->summary($cart), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/cart/{key}", methods={"POST"}, name="add")
     */
    public function add(Request $request, $key)
    {
        $cart = $this->find($key);

        $this->service->add($cart, $request->request->all());

        return $this->respond($this->service->summary($cart), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/cart/{key}", methods={"DELETE"}, name="remove")
     */
    public function remove(Request $request, $key)
    {
        $cart = $this->find($key);

        $this->service->remove($cart, $request->request->all());

        return $this->respond($this->service->summary($cart), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/cart", methods={"POST"}, name="create")
     */
    public function create(Request $request)
    {
        $cart = new Cart();

        $this->service->create($cart, $request->request->all());

        return $this->respond($this->service->summary($cart), Response::HTTP_CREATED);

    }

    private function find($id)
    {
        $cart = $this->repository->find($id);

        if (!$cart) {
            throw $this->createNotFoundException(
                'No cart found for id ' . $id
            );
        }

        return $cart;
    }
}