<?php

declare(strict_types=1);

namespace Tests\Unit\Survey\Domain\ValueObject;

use MongoDB\BSON\ObjectId as MongoObjectId;
use Survey\Domain\Exception\ObjectIdException;
use Survey\Domain\ValueObject\ObjectId;
use Tests\TestCase;

class ObjectIdUnitTest extends TestCase
{
    public function test_throws_error_when_given_an_invalid_object_id()
    {
        $objectId = '';
        $this->expectExceptionObject(ObjectIdException::itemInvalid(id: $objectId));

        new ObjectId(value: $objectId);
    }

    public function test_can_be_created_object_id()
    {
        $objectId = new MongoObjectId();

        $result = new ObjectId(value: (string)$objectId);

        $this->assertEquals((string)$objectId, $result);
    }

    public function test_can_be_random_object_id()
    {
        $objectId = ObjectId::random();

        $this->assertInstanceOf(ObjectId::class, $objectId);
    }
}
