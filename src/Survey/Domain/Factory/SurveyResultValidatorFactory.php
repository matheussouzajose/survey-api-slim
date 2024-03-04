<?php

declare(strict_types=1);

namespace Survey\Domain\Factory;

use Survey\Domain\Validator\Survey\SurveyResultValidator;
use Survey\Domain\Validator\ValidatorInterface;

class SurveyResultValidatorFactory
{
    public static function create(): ValidatorInterface
    {
        return new SurveyResultValidator();
    }
}
