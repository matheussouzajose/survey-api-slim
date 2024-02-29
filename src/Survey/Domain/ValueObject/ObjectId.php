<?php

declare(strict_types=1);

namespace Survey\Domain\ValueObject;

use MongoDB\BSON\ObjectId as MongoObjectId;
use Survey\Domain\Exception\ObjectIdException;

class ObjectId
{
    public function __construct(protected string $value)
    {
        $this->ensureIsValid($value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function random(): self
    {
        return new self((new MongoObjectId())->__toString());
    }

    private function ensureIsValid(string $id): void
    {
        try {
            new MongoObjectId($id);
        } catch (\Exception) {
            throw ObjectIdException::itemInvalid($id);
        }
    }
}
