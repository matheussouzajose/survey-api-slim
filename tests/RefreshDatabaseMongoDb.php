<?php

declare(strict_types=1);

namespace Tests;

use Survey\Infrastructure\Persistence\MongoDb\Helpers\MongoHelper;

trait RefreshDatabaseMongoDb
{
    public function tearDown(): void
    {
        $database = MongoHelper::getClient()->selectDatabase(getenv('MONGODB_DATABASE'));

        $collections = $database->listCollections();
        foreach ($collections as $collectionInfo) {
            $collectionName = $collectionInfo->getName();
            $collection = $database->selectCollection($collectionName);
            $collection->deleteMany([]);
        }
    }
}
