<?php

declare(strict_types=1);

namespace Survey\Infrastructure\Validation\SurveyResult;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;
use Survey\Ui\Api\Validation\ValidationInterface;

class SaveSurveyResultValidation implements ValidationInterface
{
    public function validate(object $input): array
    {
        try {
            $validator = v::attribute('answer', v::stringType()->length(1, 255));

            $validator->assert(input: $input);

            return [];
        } catch (ValidationException $exception) {
            return $exception->getMessages();
        }
    }
}
