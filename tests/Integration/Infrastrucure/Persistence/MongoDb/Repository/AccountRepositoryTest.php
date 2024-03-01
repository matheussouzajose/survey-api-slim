<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastrucure\Persistence\MongoDb\Repository;

use Survey\Domain\Entity\Account;
use Survey\Domain\Exception\NotificationErrorException;
use Survey\Domain\ValueObject\Email;
use Survey\Infrastructure\Persistence\MongoDb\Helpers\MongoHelper;
use Survey\Infrastructure\Persistence\MongoDb\Repository\AccountRepository;
use Tests\RefreshDatabaseMongoDb;
use Tests\TestCase;

class AccountRepositoryTest extends TestCase
{
    use RefreshDatabaseMongoDb;

    public function test_should_be_return_false_when_there_is_no_exists_email()
    {
        $accountRepository = new AccountRepository();
        $result = $accountRepository->checkByEmail(email: 'valid@mail.com');

        $this->assertFalse($result);
    }

    public function test_should_be_return_true_when_there_is_no_exists_email()
    {
        $collection = MongoHelper::getCollection('accounts');
        $collection->insertOne([
            'first_name' => 'Matheus',
            'last_name' => 'Jose',
            'email' => 'valid@mail.com',
            'password' => password_hash('123456789', PASSWORD_DEFAULT)
        ]);

        $accountRepository = new AccountRepository();
        $result = $accountRepository->checkByEmail(email: 'valid@mail.com');

        $this->assertTrue($result);
    }

    /**
     * @throws NotificationErrorException
     */
    public function test_can_be_created()
    {
        $account = new Account(
            firstName: 'Matheus',
            lastName: 'Jose',
            email: new Email('matheus.jose@mail.com'),
            password: password_hash('123456789', PASSWORD_DEFAULT)
        );
        $accountRepository = new AccountRepository();
        $result = $accountRepository->add(entity: $account);

        $this->assertInstanceOf(Account::class, $result);
    }
}
