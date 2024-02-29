<?php

declare(strict_types=1);

namespace Survey\Domain\Exception;

class EmailException extends \InvalidArgumentException
{
    public static function itemInvalid(string $value): EmailException
    {
        $message = sprintf('The email %s is invalid.', $value);

        return new self(
            message: $message,
            code: 403
        );
    }
}
