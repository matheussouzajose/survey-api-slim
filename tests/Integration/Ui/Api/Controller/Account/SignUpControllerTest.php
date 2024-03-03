<?php

declare(strict_types=1);

namespace Tests\Integration\Ui\Api\Controller\Account;

use Survey\Application\Command\SignUpCommandHandler;
use Survey\Infrastructure\Persistence\MongoDb\Helpers\MongoHelper;
use Survey\Infrastructure\Persistence\MongoDb\Repository\AccountRepository;
use Survey\Infrastructure\Validation\SignUpValidation;
use Survey\Ui\Api\Adapter\Http\HttpResponse;
use Survey\Ui\Api\Controller\Account\SignUpController;
use Tests\RefreshDatabaseMongoDb;
use Tests\TestCase;
use Tests\Unit\Mocks\Event\EventDispatcherMock;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabaseMongoDb;

    public function test_should_be_return_bad_request()
    {
        $controller = $this->createController();

        $request = new \stdClass();
        $request->first_name = '';
        $request->last_name = 'Jose';
        $request->email = 'matheus.jose@mail.com';
        $request->password = '123456789';
        $request->password_confirmation = '123456789';

        $result = ($controller)(request: $request);

        $this->assertInstanceOf(HttpResponse::class, $result);
        $this->assertEquals(400, $result->getStatusCode());
        $this->assertIsArray($result->getBody());
    }

    public function test_should_return_be_forbiden()
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
        $request->first_name = 'Matheus';
        $request->last_name = 'Jose';
        $request->email = 'matheus.jose@gmail.com';
        $request->password = '123456789';
        $request->password_confirmation = '123456789';

        $result = ($controller)(request: $request);

        $this->assertInstanceOf(HttpResponse::class, $result);
        $this->assertEquals(403, $result->getStatusCode());
        $this->assertEquals('Email Already Exists', $result->getBody());
    }

    public function test_should_return_be_ok()
    {
        $controller = $this->createController();

        $request = new \stdClass();
        $request->first_name = 'Matheus';
        $request->last_name = 'Jose';
        $request->email = 'matheus.jose@gmail.com';
        $request->password = '123456789';
        $request->password_confirmation = '123456789';

        $result = ($controller)(request: $request);

        $this->assertInstanceOf(HttpResponse::class, $result);
        $this->assertEquals(201, $result->getStatusCode());

        $this->assertEquals('Account Created', $result->getBody());
    }

    private function createController(): SignUpController
    {
        $validation = new SignUpValidation();
        $commandHandler = new SignUpCommandHandler(
            accountRepository: new AccountRepository(),
            eventDispatcher: new EventDispatcherMock()
        );

        return new SignUpController(
            validation: $validation,
            commandHandler: $commandHandler
        );
    }
}
