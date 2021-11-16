<?php

namespace App\Tests\Common;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{
    private static KernelBrowser $staticClient;

    protected $client;

    public static function setUpBeforeClass(): void
    {
        self::$staticClient = static::createClient();

        parent::setUpBeforeClass();
    }

    protected function setUp(): void
    {
        $this->client = self::$staticClient;

        parent::setUp();
    }
}