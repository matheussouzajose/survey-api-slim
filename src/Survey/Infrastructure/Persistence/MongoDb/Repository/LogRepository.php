<?php

declare(strict_types=1);

namespace Survey\Infrastructure\Persistence\MongoDb\Repository;

use MongoDB\Collection;
use Survey\Domain\Repository\LogRepositoryInterface;
use Survey\Infrastructure\Persistence\MongoDb\Helpers\MongoHelper;

class LogRepository implements LogRepositoryInterface
{
    public Collection $collection;

    public function __construct()
    {
        $this->collection = MongoHelper::getCollection('log_errors');
    }
    public function logError(array $errors): void
    {
        $this->collection->insertOne([
            'message' => $errors['message'],
            'trace' => $errors['trace'],
            'created_at' => (new \DateTime())->format('Y-m-d H:i:s')
        ]);
    }
}
