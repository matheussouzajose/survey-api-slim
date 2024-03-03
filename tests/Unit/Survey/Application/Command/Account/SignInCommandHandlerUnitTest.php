<?php

declare(strict_types=1);

namespace Tests\Unit\Survey\Application\Command\Account;

use Survey\Application\Command\SignInCommand;
use Survey\Application\Command\SignInCommandHandler;
use Survey\Application\Interfaces\Cryptography\EncrypterInterface;
use Survey\Domain\Entity\Account;
use Survey\Domain\Repository\AccountRepositoryInterface;
use Survey\Domain\ValueObject\Email;
use Survey\Domain\ValueObject\ObjectId;
use Tests\TestCase;

class SignInCommandHandlerUnitTest extends TestCase
{
    private function mockEntity(string $objectId)
    {
        $mockEntity = \Mockery::mock(Account::class, [
            'Matheus',
            'Jose',
            new Email('matheus.jose@mail.com'),
            123456789,
            null,
            new ObjectId(value: $objectId)
        ]);

        $mockEntity->shouldReceive('id')->andReturn($objectId);
        $mockEntity->shouldReceive('password')->andReturn(password_hash('123456789', PASSWORD_DEFAULT));
        $mockEntity->shouldReceive('changeAccessToken');
        $mockEntity->shouldReceive('firstName')->andReturn('Matheus');
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        return $mockEntity;
    }

    private function mockRepository()
    {
        $mockRepository = \Mockery::mock(\stdClass::class, AccountRepositoryInterface::class);
        $mockRepository->shouldReceive('updateAccessToken');

        return $mockRepository;
    }

    private function mockEncrypter()
    {
        $mockEncrypter = \Mockery::mock(\stdClass::class, EncrypterInterface::class);
        $mockEncrypter->shouldReceive('encrypt')->andReturn('token');

        return $mockEncrypter;
    }

    public function test_should_return_false_there_is_no_account()
    {
        $accountRepository = $this->mockRepository();
        $accountRepository->shouldReceive('loadByEmail')
            ->andReturn(null);

        $encrypter = $this->mockEncrypter();

        $command = new SignInCommand(email: 'matheus.jose@mail.com', password: '123456789');
        $commandHandler = new SignInCommandHandler(accountRepository: $accountRepository, encrypter: $encrypter);

        $result = ($commandHandler)($command);

        $this->assertFalse($result['success']);
        $this->assertEquals('Credentials invalid', $result['message']);
    }

    public function test_should_return_false_password_are_different()
    {
        $accountRepository = $this->mockRepository();
        $accountRepository->shouldReceive('loadByEmail')
            ->andReturn($this->mockEntity((string)ObjectId::random()));

        $encrypter = $this->mockEncrypter();

        $command = new SignInCommand(email: 'matheus.jose@mail.com', password: '12345678910');
        $commandHandler = new SignInCommandHandler(accountRepository: $accountRepository, encrypter: $encrypter);

        $result = ($commandHandler)($command);

        $this->assertFalse($result['success']);
        $this->assertEquals('Credentials invalid', $result['message']);
    }

    public function test_can_be_logged()
    {
        $accountRepository = $this->mockRepository();
        $accountRepository->shouldReceive('loadByEmail')
            ->andReturn($this->mockEntity((string)ObjectId::random()));

        $encrypter = $this->mockEncrypter();

        $command = new SignInCommand(email: 'matheus.jose@mail.com', password: '123456789');
        $commandHandler = new SignInCommandHandler(accountRepository: $accountRepository, encrypter: $encrypter);

        $result = ($commandHandler)($command);

        $this->assertEquals([
            'access_token' => 'token',
            'token_type' => 'Bearer',
        ], $result['result']['authentication']);
    }

    protected function tearDown(): void
    {
        \Mockery::close();

        parent::tearDown();
    }
}
