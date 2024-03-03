<?php

declare(strict_types=1);

namespace Survey\Infrastructure\Persistence\MongoDb\Repository;

use MongoDB\Collection;
use Survey\Domain\Entity\Account;
use Survey\Domain\Exception\NotificationErrorException;
use Survey\Domain\Repository\AccountRepositoryInterface;
use Survey\Domain\ValueObject\Email;
use Survey\Domain\ValueObject\ObjectId;
use Survey\Infrastructure\Persistence\MongoDb\Helpers\MongoHelper;
use Survey\Infrastructure\Persistence\MongoDb\Helpers\QueryBuilder;

class AccountRepository implements AccountRepositoryInterface
{
    public Collection $collection;

    public function __construct()
    {
        $this->collection = MongoHelper::getCollection('accounts');
    }

    public function checkByEmail(string $email): bool
    {
        $filter = ['email' => $email];
        $options = ['projection' => ['_id' => 1]];
        $account = $this->collection->findOne($filter, $options);

        return !is_null($account);
    }

    /**
     * @throws NotificationErrorException
     */
    public function add(Account $entity): Account
    {
        $this->collection->insertOne([
            '_id' => $entity->id(),
            'first_name' => $entity->firstName(),
            'last_name' => $entity->lastName(),
            'email' => $entity->email()->value(),
            'password' => $entity->password(),
            'created_at' => $entity->createdAt(),
        ]);

        $result = $this->collection->findOne(['_id' => $entity->id()]);

        return $this->createEntity($result);
    }

    /**
     * @throws NotificationErrorException
     * @throws \Exception
     */
    private function createEntity(object $entity): Account
    {
        return new Account(
            firstName: $entity->first_name,
            lastName: $entity->last_name,
            email: new Email($entity->email),
            password: $entity->password,
            accessToken: $entity->access_token ?? null,
            id: new ObjectId((string)$entity->_id),
            createdAt: isset($entity->created_at) ? new \DateTime($entity->created_at) : null,
            updatedAt: isset($entity->updated_at) ? new \DateTime($entity->updated_at) : null
        );
    }

    /**
     * @throws NotificationErrorException
     */
    public function loadByEmail(string $email): ?Account
    {
        $account = $this->collection->findOne(['email' => $email]);

        return $account ? $this->createEntity($account) : null;
    }

    public function updateAccessToken(Account $entity): ?int
    {
        $builder = new QueryBuilder();
        $builder->set([
            'access_token' => $entity->accessToken(),
            'updated_at' => $entity->updatedAt()
        ]);

        $result = $this->collection->updateOne([
            '_id' =>  $entity->id()
        ], $builder->build());

        return $result->getModifiedCount();
    }

    public function checkByToken(string $token): bool
    {
        $filter = ['access_token' => $token];
        $options = ['projection' => ['_id' => 1]];
        $account = $this->collection->findOne($filter, $options);

        return !is_null($account);
    }
}
