<?php

declare(strict_types=1);

namespace Survey\Main\Factories\Repository;

use Survey\Infrastructure\Persistence\MongoDb\Repository\AccountRepository;

class AccountRepositoryFactory
{
    public static function create(): AccountRepository
    {
        return new AccountRepository();
    }
}
