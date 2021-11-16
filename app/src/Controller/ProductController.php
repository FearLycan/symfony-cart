<?php

namespace App\Controller;

use App\Common\Controller\ApiController;
use App\Entity\Product;
use App\Form\Type\ProductType;
use App\Repository\ProductRepository;
use App\Service\ErrorHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 * @Route("/api", name="products_")
 */
class ProductController extends ApiController
{
    private EntityManagerInterface $em;
    private ProductRepository $repository;
    private ErrorHandlerInterface $errorHandler;

    public function __construct(EntityManagerInterface $em, ProductRepository $productRepository,
                                ErrorHandlerInterface $errorHandler)
    {
        $this->em = $em;
        $this->repository = $productRepository;
        $this->errorHandler = $errorHandler;
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/products", methods={"POST"}, name="create")
     */
    public function create(Request $request)
    {
        $product = new Product();
        $form = $this->buildForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($product);

            $this->em->flush();

            return $this->respond($product, Response::HTTP_CREATED);
        } else {
            return $this->respond($this->errorHandler->formHandler($form), Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * @param int $page
     * @return JsonResponse
     * @Route("/products", methods={"GET"}, name="list-1")
     * @Route("/products/page/{page}", methods={"GET"}, name="list-2")
     */
    public function products(int $page = 1)
    {
        return $this->json($this->repository->getByPage($page));
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @Route("/products/{id}", methods={"GET"}, name="show")
     */
    public function show($id)
    {
        $product = $this->find($id);

        return $this->respond($product, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @Route("/products/{id}", methods={"DELETE"}, name="delete")
     */
    public function delete($id)
    {
        $product = $this->find($id);

        try {
            $this->em->remove($product);
            $this->em->flush();

            return $this->respond(['Product has been removed'], Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->respond(['INTERNAL_SERVER_ERROR'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function find($id)
    {
        $product = $this->repository->find($id);

        if (!$product) {
            //return $this->respond(['error' => 'No product found for id ' . $id], Response::HTTP_NOT_FOUND);
            if (!$product) {
                throw $this->createNotFoundException(
                    'No product found for id ' . $id
                );
            }
        }

        return $product;
    }
}