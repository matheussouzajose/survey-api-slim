<?php

declare(strict_types=1);

namespace Survey\Domain\Factory;

use Survey\Domain\Validator\ValidatorInterface;
use Survey\Infrastructure\Validator\AccountValidator;

class AccountValidatorFactory
{
    public static function create(): ValidatorInterface
    {
        return new AccountValidator();
    }
}
