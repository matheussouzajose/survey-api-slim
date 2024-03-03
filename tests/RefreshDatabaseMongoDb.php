<?php

declare(strict_types=1);

namespace Tests;

use Survey\Infrastructure\Persistence\MongoDb\Helpers\MongoHelper;

trait RefreshDatabaseMongoDb
{
    public function tearDown(): void
    {
//        var_dump(getenv('MONGODB_DATABASE'));
        $database = MongoHelper::getClient()->selectDatabase(MongoHelper::getDatabase());

        $collections = $database->listCollections();
        foreach ($collections as $collectionInfo) {
            $collectionName = $collectionInfo->getName();
            $collection = $database->selectCollection($collectionName);
            $collection->deleteMany([]);
        }
    }
}
