<?php

declare(strict_types=1);

namespace Survey\Application\Traits;

trait ResponseTrait
{
    public static function success(string $message): array
    {
        return [
            'success' => true,
            'message' => $message
        ];
    }

    public static function error(string $message): array
    {
        return [
            'success' => false,
            'message' => $message
        ];
    }
}
