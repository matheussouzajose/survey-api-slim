<?php

declare(strict_types=1);

namespace Survey\Infrastructure\Validator;

use Survey\Domain\Entity\Entity;
use Survey\Domain\Validator\ValidatorInterface;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class AccountValidator implements ValidatorInterface
{
    public const CONTEXT = 'account';

    public function validate(Entity $entity): void
    {
        try {
            $validator = v::attribute(
                'firstName',
                v::stringType()->length(1, 255)
            )
                ->attribute(
                    'lastName',
                    v::stringType()->length(1, 255)
                )
                ->attribute(
                    'password',
                    v::stringType()->length(6, 255)
                );

            $validator->assert(input: $entity);
        } catch (ValidationException $exception) {
            foreach ($exception->getMessages() as $error) {
                $entity->notificationErrors->addError([
                    'context' => self::CONTEXT,
                    'message' => $error,
                ]);
            }
        }
    }
}
