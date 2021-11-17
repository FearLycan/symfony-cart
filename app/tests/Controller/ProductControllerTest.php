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

        //$this->assertJsonStringEqualsJsonFile(__DIR__ . '/../Common/File/products.json', $content);
    }

    public function testProductFound()
    {
        $this->client->request(Request::METHOD_GET, '/api/products/1');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $content = $this->client->getResponse()->getContent();
        $this->assertJson($content);

        //$this->assertJsonStringEqualsJsonFile(__DIR__ . '/../Common/File/product.json', $content);
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

    public function testRemove()
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

        $product = json_decode($this->client->getResponse()->getContent(), true);


        $this->client->request(
            Request::METHOD_DELETE,
            '/api/products/' . $product['id'],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([])
        );

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testEdit()
    {
        $data1 = [
            'name' => 'Product Name',
            'price' => 33.33,
            'currency' => 'PLN',
        ];

        $product = $this->createNewProduct($data1);
//dump($product);
        $data2 = [
            'name' => 'Test product Name',
            'price' => 44.44,
            'currency' => 'USD',
        ];

        $this->client->request(
            Request::METHOD_PATCH,
            '/api/products/' . $product['id'],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data2)
        );
//dd($this->client->getResponse());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        $updatedProduct = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertSame($data2['name'], $updatedProduct['name']);
        $this->assertSame($data2['price'], $updatedProduct['price']);
        $this->assertSame($data2['currency'], $updatedProduct['currency']);
    }

    private function createNewProduct($data)
    {
        $this->client->request(
            Request::METHOD_POST,
            '/api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $product = json_decode($this->client->getResponse()->getContent(), true);

        return $product;
    }
}