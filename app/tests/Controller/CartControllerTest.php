<?php

namespace App\Tests\Controller;

use App\Tests\Common\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartControllerTest extends WebTestCase
{
    public $cart;

    public function testCreate()
    {
        $data = [
            'product_id' => 1,
        ];

        $this->client->request(
            Request::METHOD_POST,
            '/api/cart',
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
            'product_id' => 1,
        ];

        $this->client->request(
            Request::METHOD_POST,
            '/api/cart',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function testCartNotFound()
    {
        $this->client->request(Request::METHOD_GET, '/api/cart/01FMNP4PR4K1J43GQHH02CA0D0');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
    }
}