<?php

declare(strict_types=1);

namespace Tests\Functional\Api;

use GuzzleHttp\Client;
use Tests\TestCase;

class HelfCheckApiTest extends TestCase
{
    protected Client $httpClient;
    protected function setUp(): void
    {
        parent::setUp();

        $this->httpClient = new Client([
            'base_uri' => 'http://localhost:9000'
        ]);
    }

    public function test_can_be_check()
    {
        $result = $this->httpClient->get('/ping');

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals('pong', $result->getBody()->getContents());
    }
}
