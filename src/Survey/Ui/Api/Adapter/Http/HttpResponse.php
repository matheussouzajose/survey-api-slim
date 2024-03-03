<?php

declare(strict_types=1);

namespace Survey\Ui\Api\Adapter\Http;

class HttpResponse
{
    public function __construct(protected int $statusCode, protected $body)
    {
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
