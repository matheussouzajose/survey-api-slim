<?php

declare(strict_types=1);

namespace Tests\Unit\Survey\Application\Command\Account;

use Survey\Application\Command\SignUpCommand;
use Survey\Application\Command\SignUpCommandHandler;
use Survey\Domain\Entity\Account;
use Survey\Domain\Event\Account\AccountCreatedEvent;
use Survey\Domain\Event\EventDispatcherInterface;
use Survey\Domain\Repository\AccountRepositoryInterface;
use Survey\Domain\ValueObject\Email;
use Survey\Domain\ValueObject\ObjectId;
use Tests\TestCase;

class SignUpCommandHandlerUnitTest extends TestCase
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
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        return $mockEntity;
    }

    private function mockRepository(string $objectId, int $timesCalled = 1)
    {
        $mockRepository = \Mockery::mock(\stdClass::class, AccountRepositoryInterface::class);
        $mockRepository->shouldReceive('add')
            ->times($timesCalled)
            ->andReturn($this->mockEntity($objectId));

        return $mockRepository;
    }

    private function mockEventDispatcher(int $timesCalled = 1)
    {
        $mockEventDispatcher = \Mockery::mock(\stdClass::class, EventDispatcherInterface::class);
        $mockEventDispatcher->shouldReceive('notify')->times($timesCalled);

        return $mockEventDispatcher;
    }

    public function test_should_be_return_false_when_email_already_exists()
    {
        $command = new SignUpCommand(
            firstName: 'Matheus',
            lastName: 'Jose',
            email: 'maheus.jose@mail.com',
            password: '123456789',
            passwordConfirmation: '123456789'
        );

        $accountRepository = $this->mockRepository(objectId: (string)ObjectId::random(), timesCalled: 0);
        $accountRepository->shouldReceive('checkByEmail')->andReturn(true);

        $eventDispatcher = $this->mockEventDispatcher(timesCalled: 0);

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
        $command = new SignUpCommand(
            firstName: 'Matheus',
            lastName: 'Jose',
            email: 'maheus.jose@mail.com',
            password: '123456789',
            passwordConfirmation: '12345678910'
        );

        $accountRepository = $this->mockRepository(objectId: (string)ObjectId::random(), timesCalled: 0);
        $accountRepository->shouldReceive('checkByEmail')->andReturn(false);

        $eventDispatcher = $this->mockEventDispatcher(timesCalled: 0);

        $commandHandler = new SignUpCommandHandler(
            accountRepository: $accountRepository,
            eventDispatcher: $eventDispatcher
        );

        $result = ($commandHandler)(command: $command);

        $this->assertFalse($result['success']);
        $this->assertEquals('The passwords don\'t match', $result['message']);
    }

    public function test_can_be_created_account()
    {
        $command = new SignUpCommand(
            firstName: 'Matheus',
            lastName: 'Jose',
            email: 'maheus.jose@mail.com',
            password: '123456789',
            passwordConfirmation: '123456789'
        );

        $accountRepository = $this->mockRepository(objectId: (string)ObjectId::random());
        $accountRepository->shouldReceive('checkByEmail')->andReturn(false);

        $eventDispatcher = $this->mockEventDispatcher();

        $commandHandler = new SignUpCommandHandler(
            accountRepository: $accountRepository,
            eventDispatcher: $eventDispatcher
        );

        $result = ($commandHandler)(command: $command);

        $accountRepository->shouldHaveReceived('add')->with(Account::class)->once();
        $eventDispatcher->shouldHaveReceived('notify')->with(AccountCreatedEvent::class)->once();

        $this->assertTrue($result['success']);
        $this->assertEquals('Account Created', $result['message']);
    }
    protected function tearDown(): void
    {
        \Mockery::close();

        parent::tearDown();
    }

}
