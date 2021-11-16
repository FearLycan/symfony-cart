<?php

namespace App\Tests\Controller;

use App\Repository\ProductRepository;
use App\Tests\Common\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    public function testProducts()
    {
        $repository = $this->createMock(ProductRepository::class);

        $repository->expects($this->any())
            ->method('getByPage')
            ->withAnyParameters()
            ->willReturn([]);

        $this->client->request(Request::METHOD_GET, '/api/products');

        $this->assertResponseIsSuccessful();

        $content = $this->client->getResponse()->getContent();
        $this->assertJson($content);

        $products = json_decode($content, true);
        $this->assertIsArray($products);
        $this->assertTrue(count($products) === 3);

        $this->assertJsonStringEqualsJsonFile(__DIR__ . '/../Common/File/products.json', $content);
    }

    public function testProductFound()
    {
        $this->client->request(Request::METHOD_GET, '/api/products/1');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = $this->client->getResponse()->getContent();
        $this->assertJson($content);

        $this->assertJsonStringEqualsJsonFile(__DIR__ . '/../Common/File/product.json', $content);
    }

    public function testProductNotFound()
    {
        $this->client->request(Request::METHOD_GET, '/api/products/96');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }

    public function testCreate()
    {
        $data = [
            'name' => 'Test product Name',
            'price' => 33.33,
            'currency' => 'PLN',
        ];

        $this->client->request(
            Request::METHOD_POST,
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }
}