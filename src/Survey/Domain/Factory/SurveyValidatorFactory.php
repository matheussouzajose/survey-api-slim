<?php

declare(strict_types=1);

namespace Survey\Domain\Factory;

use Survey\Domain\Validator\Survey\SurveyValidator;
use Survey\Domain\Validator\ValidatorInterface;

class SurveyValidatorFactory
{
    public static function create(): ValidatorInterface
    {
        return new SurveyValidator();
    }
}
