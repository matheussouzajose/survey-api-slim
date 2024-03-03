<?php

declare(strict_types=1);

namespace Survey\Main\Factories\Repository;

use Survey\Domain\Repository\LogRepositoryInterface;
use Survey\Infrastructure\Persistence\MongoDb\Repository\LogRepository;

class LogRepositoryFactory
{
    public static function create(): LogRepositoryInterface
    {
        return new LogRepository();
    }
}
