<?php

declare(strict_types=1);

namespace Survey\Infrastructure\Validation;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;
use Survey\Ui\Api\Validation\ValidationInterface;

class SignInValidation implements ValidationInterface
{
    public function validate(object $input): array
    {
        try {
            $validator = v::attribute('email', v::email())
                ->attribute('password', v::stringType()->length(6, null));

            $validator->assert(input: $input);

            return [];
        } catch (ValidationException $exception) {
            return $exception->getMessages();
        }
    }
}
