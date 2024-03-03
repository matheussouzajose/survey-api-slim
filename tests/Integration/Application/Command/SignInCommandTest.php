<?php

declare(strict_types=1);

namespace Tests\Integration\Application\Command;

use Survey\Application\Command\SignInCommand;
use Survey\Application\Command\SignInCommandHandler;
use Survey\Infrastructure\Cryptography\JwtAdapter\JwtAdapter;
use Survey\Infrastructure\Persistence\MongoDb\Helpers\MongoHelper;
use Survey\Infrastructure\Persistence\MongoDb\Repository\AccountRepository;
use Tests\RefreshDatabaseMongoDb;
use Tests\TestCase;

class SignInCommandTest extends TestCase
{
    use RefreshDatabaseMongoDb;

    public function test_should_be_return_false_when_email_no_exists()
    {
        $accountRepository = new AccountRepository();
        $jwtAdapter = new JwtAdapter(secretKey: 'SECRET_JWT');

        $command = new SignInCommand(email: 'matheus.jose@gmail.com', password: '123456789');
        $commandHandler = new SignInCommandHandler(accountRepository: $accountRepository, encrypter: $jwtAdapter);

        $result = ($commandHandler)(command: $command);

        $this->assertFalse($result['success']);
        $this->assertEquals('Credentials invalid', $result['message']);
    }

    public function test_should_be_return_false_when_given_different_passwords()
    {
        $collection = MongoHelper::getCollection('accounts');
        $collection->insertOne([
            'first_name' => 'Matheus',
            'last_name' => 'Jose',
            'email' => 'matheus.jose@gmail.com',
            'password' => password_hash('123456789', PASSWORD_DEFAULT)
        ]);

        $accountRepository = new AccountRepository();
        $jwtAdapter = new JwtAdapter(secretKey: 'SECRET_JWT');

        $command = new SignInCommand(email: 'matheus.jose@gmail.com', password: '12345678910');
        $commandHandler = new SignInCommandHandler(accountRepository: $accountRepository, encrypter: $jwtAdapter);

        $result = ($commandHandler)(command: $command);

        $this->assertFalse($result['success']);
        $this->assertEquals('Credentials invalid', $result['message']);
    }

    public function test_can_be_logged()
    {
        $collection = MongoHelper::getCollection('accounts');
        $collection->insertOne([
            'first_name' => 'Matheus',
            'last_name' => 'Jose',
            'email' => 'matheus.jose@gmail.com',
            'password' => password_hash('123456789', PASSWORD_DEFAULT)
        ]);

        $accountRepository = new AccountRepository();
        $jwtAdapter = new JwtAdapter(secretKey: 'SECRET_JWT');

        $command = new SignInCommand(email: 'matheus.jose@gmail.com', password: '123456789');
        $commandHandler = new SignInCommandHandler(accountRepository: $accountRepository, encrypter: $jwtAdapter);

        $result = ($commandHandler)(command: $command);

        $this->assertNotEmpty($result['authentication']['access_token']);
        $this->assertEquals('Bearer', $result['authentication']['token_type']);
        $this->assertEquals('Matheus', $result['name']);
    }
}
