<?php

declare(strict_types=1);

namespace Survey\Domain\ValueObject;

use Respect\Validation\Validator as v;
use Survey\Domain\Exception\EmailException;

class Email
{
    public function __construct(protected string $value)
    {
        $this->ensureIsValid($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    private function ensureIsValid(string $value): void
    {
        if ( !v::email()->validate(input: $value) ) {
            throw EmailException::itemInvalid(value: $value);
        }
    }
}
