<?php

declare(strict_types=1);

namespace Survey\Domain\Factory;

use Survey\Domain\Validator\Account\AccountValidator;
use Survey\Domain\Validator\ValidatorInterface;

class AccountValidatorFactory
{
    public static function create(): ValidatorInterface
    {
        return new AccountValidator();
    }
}
