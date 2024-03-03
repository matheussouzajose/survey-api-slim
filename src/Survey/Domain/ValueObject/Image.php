<?php

declare(strict_types=1);

namespace Survey\Domain\ValueObject;

class Image
{
    public function __construct(protected string $path)
    {
    }

    public function path(): string
    {
        return $this->path;
    }
}
