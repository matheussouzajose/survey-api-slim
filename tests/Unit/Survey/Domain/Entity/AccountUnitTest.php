<?php

declare(strict_types=1);

namespace Tests\Unit\Survey\Domain\Entity;

use Survey\Domain\Entity\Account;
use Survey\Domain\Exception\NotificationErrorException;
use Survey\Domain\ValueObject\Email;
use Tests\TestCase;

class AccountUnitTest extends TestCase
{
    /**
     * @dataProvider providerInvalidAttributes
     */
    public function test_throws_error_when_given_an_invalid_data(string $firstName, string $lastName, string $password)
    {
        $this->expectException(NotificationErrorException::class);

        new Account(
            firstName: $firstName,
            lastName: $lastName,
            email: new Email('valid@mail.com'),
            password: $password
        );
    }

    public static function providerInvalidAttributes(): array
    {
        return [
            ['', 'Jose', '123456789'],
            ['Matheus', '', '123456789'],
            ['Matheus', 'Jose', ''],
        ];
    }

    public function test_can_be_created_account()
    {
        $firstName = 'Matheus';
        $lastName = 'Jose';
        $email = new Email('valid@email.com');
        $password = '123456789';

        $account = new Account(firstName: $firstName, lastName: $lastName, email: $email, password: $password);

        $this->assertNotEmpty($account->id());
        $this->assertNotEmpty($account->createdAt());
        $this->assertEquals($firstName, $account->firstName());
        $this->assertEquals($lastName, $account->lastName());
        $this->assertEquals($email, $account->email());
        $this->assertNull($account->updatedAt());
        $this->assertNull($account->accessToken());
    }

    public function test_can_be_change_full_name()
    {
        $account = $this->createAccount();

        $firstName = 'Joao';
        $lastName = 'Pedro';
        $account->changeName(firstName: $firstName, lastName: $lastName);

        $this->assertEquals($firstName, $account->firstName());
        $this->assertEquals($lastName, $account->lastName());
        $this->assertNotEmpty($account->updatedAt());
    }

    public function test_can_be_change_email()
    {
        $account = $this->createAccount();

        $email = new Email('valid@mail.com');
        $account->changeEmail(email: $email);

        $this->assertEquals($email, $account->email());
        $this->assertNotEmpty($account->updatedAt());
    }

    public function test_can_be_change_password()
    {
        $account = $this->createAccount();

        $password = 'new_password';
        $account->changePassword(password: $password);

        $this->assertEquals($password, $account->password());
        $this->assertNotEmpty($account->updatedAt());
    }

    public function test_can_be_change_access_token()
    {
        $account = $this->createAccount();

        $accessToken = 'new_access_token';
        $account->changeAccessToken(token: $accessToken);

        $this->assertEquals($accessToken, $account->accessToken());
    }

    /**
     * @throws NotificationErrorException
     */
    private function createAccount(): Account
    {
        return new Account(
            firstName: 'Matheus',
            lastName: 'Jose',
            email: new Email('valid@email.com'),
            password: '123456789'
        );
    }
}
