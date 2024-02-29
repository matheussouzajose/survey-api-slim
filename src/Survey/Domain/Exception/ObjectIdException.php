<?php

declare(strict_types=1);

namespace Survey\Domain\Exception;

class ObjectIdException extends \InvalidArgumentException
{
    public static function itemInvalid(string $id): ObjectIdException
    {
        $message = sprintf('The objectId %s is invalid.', $id);

        return new self(
            message: $message,
            code: 403
        );
    }
}
