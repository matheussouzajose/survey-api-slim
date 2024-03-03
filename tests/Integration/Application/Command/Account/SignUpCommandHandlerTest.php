<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Command\Account;

use Survey\Application\Command\Account\SignUpCommand;
use Survey\Application\Command\Account\SignUpCommandHandler;
use Survey\Infrastructure\Persistence\MongoDb\Helpers\MongoHelper;
use Survey\Infrastructure\Persistence\MongoDb\Repository\AccountRepository;
use Tests\RefreshDatabaseMongoDb;
use Tests\TestCase;
use Tests\Unit\Mocks\Event\EventDispatcherMock;

class SignUpCommandHandlerTest extends TestCase
{
    use RefreshDatabaseMongoDb;

    public function test_should_be_return_false_when_email_already_exists()
    {
        $collection = MongoHelper::getCollection('accounts');
        $collection->insertOne([
            'first_name' => 'Matheus',
            'last_name' => 'Jose',
            'email' => 'matheus.jose@gmail.com',
            'password' => password_hash('123456789', PASSWORD_DEFAULT)
        ]);

        $accountRepository = new AccountRepository();
        $eventDispatcher = new EventDispatcherMock();

        $command = new SignUpCommand(
            firstName: 'Matheus',
            lastName: 'Jose',
            email: 'matheus.jose@gmail.com',
            password: '123456789',
            passwordConfirmation: '123456789'
        );

        $commandHandler = new SignUpCommandHandler(
            accountRepository: $accountRepository,
            eventDispatcher: $eventDispatcher
        );

        $result = ($commandHandler)(command: $command);

        $this->assertFalse($result['success']);
        $this->assertEquals('Email Already Exists', $result['message']);
    }

    public function test_should_be_return_false_when_given_different_passwords()
    {
        $accountRepository = new AccountRepository();
        $eventDispatcher = new EventDispatcherMock();

        $command = new SignUpCommand(
            firstName: 'Matheus',
            lastName: 'Jose',
            email: 'matheus.jose@gmail.com',
            password: '123456789',
            passwordConfirmation: '12345678910'
        );

        $commandHandler = new SignUpCommandHandler(
            accountRepository: $accountRepository,
            eventDispatcher: $eventDispatcher
        );

        $result = ($commandHandler)(command: $command);

        $this->assertFalse($result['success']);
        $this->assertEquals('The passwords don\'t match', $result['message']);
    }

    public function test_can_be_add_account()
    {
        $accountRepository = new AccountRepository();
        $eventDispatcher = new EventDispatcherMock();

        $command = new SignUpCommand(
            firstName: 'Matheus',
            lastName: 'Jose',
            email: 'matheus.jose@gmail.com',
            password: '123456789',
            passwordConfirmation: '123456789'
        );

        $commandHandler = new SignUpCommandHandler(
            accountRepository: $accountRepository,
            eventDispatcher: $eventDispatcher
        );

        $result = ($commandHandler)(command: $command);

        $this->assertTrue($result['success']);
        $this->assertEquals('Account Created', $result['success']);
    }
}
