<?php

declare(strict_types=1);

namespace Survey\Infrastructure\Validation\Survey;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;
use Survey\Ui\Api\Validation\ValidationInterface;

class AddSurveyValidation implements ValidationInterface
{
    public function validate(object $input): array
    {
        try {

            $answerValidator = v::arrayType()->each(
                v::key('answer', v::stringType()->notEmpty()),
                v::key('image', v::stringType()->notEmpty())
            );

            $validator = v::keySet(
                v::key('question', v::stringType()->notEmpty()),
                v::key('answers', $answerValidator)
            );

            unset($input->user_id);

            $validator->assert(input: (array)$input);

            return [];
        } catch (ValidationException $exception) {
            return $exception->getMessages();
        }
    }
}
