<?php

declare(strict_types=1);

namespace Survey\Infrastructure\Validation;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;
use Survey\Ui\Api\Validation\ValidationInterface;

class SignUpValidation implements ValidationInterface
{
    public function validate(object $input): array
    {
        try {
            $validator = v::attribute('first_name', v::stringType()->length(1, 255))
                ->attribute('last_name', v::stringType()->length(1, 255))
                ->attribute('email', v::email())
                ->attribute('password', v::stringType()->length(6, null))
                ->attribute('password_confirmation', v::equals($input->password ?? null));

            $validator->assert(input: $input);

            return [];
        } catch (ValidationException $exception) {
            return $exception->getMessages();
        }
    }
}
