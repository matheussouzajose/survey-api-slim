<?php

declare(strict_types=1);

namespace Tests\Functional\Api;

use GuzzleHttp\Client;
use Tests\RefreshDatabaseMongoDb;
use Tests\TestCase;

class AccountApiTest extends TestCase
{
    use RefreshDatabaseMongoDb;

    protected Client $httpClient;
    protected string $prefix = '/api/v1';

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpClient = new Client([
            'base_uri' => 'http://localhost:9000'
        ]);
    }

    public function test_should_be_validations()
    {
    }
}
