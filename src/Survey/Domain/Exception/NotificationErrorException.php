<?php

declare(strict_types=1);

namespace Survey\Domain\Exception;

class NotificationErrorException extends \Exception
{
    public static function messages(string $message): NotificationErrorException
    {
        return new self(
            message: $message,
            code: 403
        );
    }
}
