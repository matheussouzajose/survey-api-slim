<?php

declare(strict_types=1);

namespace Tests\Integration\Ui\Api\Controller\Account;

use Survey\Application\Command\SignInCommandHandler;
use Survey\Infrastructure\Cryptography\JwtAdapter\JwtAdapter;
use Survey\Infrastructure\Persistence\MongoDb\Helpers\MongoHelper;
use Survey\Infrastructure\Persistence\MongoDb\Repository\AccountRepository;
use Survey\Infrastructure\Validation\SignInValidation;
use Survey\Ui\Api\Adapter\Http\HttpResponse;
use Survey\Ui\Api\Controller\Account\SignInController;
use Tests\RefreshDatabaseMongoDb;
use Tests\TestCase;

class SignInControllerTest extends TestCase
{
    use RefreshDatabaseMongoDb;

    public function test_should_be_return_bad_request()
    {
        $controller = $this->createController();

        $request = new \stdClass();
        $request->email = '';
        $request->password = '';

        $result = ($controller)(request: $request);

        $this->assertInstanceOf(HttpResponse::class, $result);
        $this->assertEquals(400, $result->getStatusCode());
        $this->assertIsArray($result->getBody());
    }

    public function test_should_return_be_unauthorized()
    {
        $controller = $this->createController();

        $request = new \stdClass();
        $request->email = 'matheus.jose@gmail.com';
        $request->password = '12345678910';

        $result = ($controller)(request: $request);

        $this->assertInstanceOf(HttpResponse::class, $result);
        $this->assertEquals(401, $result->getStatusCode());
        $this->assertEquals('Unauthorized', $result->getBody());
    }

    public function test_should_return_be_ok()
    {
        $collection = MongoHelper::getCollection('accounts');
        $collection->insertOne([
            'first_name' => 'Matheus',
            'last_name' => 'Jose',
            'email' => 'matheus.jose@gmail.com',
            'password' => password_hash('123456789', PASSWORD_DEFAULT)
        ]);

        $controller = $this->createController();

        $request = new \stdClass();
        $request->email = 'matheus.jose@gmail.com';
        $request->password = '123456789';

        $result = ($controller)(request: $request);

        $this->assertInstanceOf(HttpResponse::class, $result);
        $this->assertEquals(200, $result->getStatusCode());

        $this->assertIsArray($result->getBody()['authentication']);
        $this->assertEquals('Matheus', $result->getBody()['name']);
    }

    private function createController(): SignInController
    {
        $validation = new SignInValidation();
        $commandHandler = new SignInCommandHandler(
            accountRepository: new AccountRepository(),
            encrypter: new JwtAdapter('SECRET_KEY')
        );

        return new SignInController(
            validation: $validation,
            commandHandler: $commandHandler
        );
    }
}
