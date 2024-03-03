<?php

declare(strict_types=1);

namespace Survey\Application\Traits;

trait ResponseTrait
{
    public static function success(string $message = '', array $body = []): array
    {
        $response = [
            'success' => true,
            'message' => $message
        ];

        if (count($body) > 0) {
            $response['result'] = $body;
        }

        return $response;
    }

    public static function error(string $message): array
    {
        return [
            'success' => false,
            'message' => $message
        ];
    }
}
