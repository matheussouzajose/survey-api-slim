<?php

declare(strict_types=1);

namespace Survey\Domain\Validator\Survey;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;
use Survey\Domain\Entity\Entity;
use Survey\Domain\Validator\ValidatorInterface;
use Survey\Domain\ValueObject\SurveyAnswer;

class SurveyValidator implements ValidatorInterface
{
    public const CONTEXT = 'survey';

    public function validate(Entity $entity): void
    {
        try {
            $validator = v::attribute(
                'question',
                v::stringType()->length(1, 255)
            )
                ->attribute(
                    'surveyAnswer',
                    v::arrayType()->each(v::instance(SurveyAnswer::class))
                )
                ->attribute(
                    'didAnswer',
                    v::boolType()
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
