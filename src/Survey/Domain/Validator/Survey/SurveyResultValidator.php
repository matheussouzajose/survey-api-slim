<?php

declare(strict_types=1);

namespace Survey\Domain\Validator\Survey;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;
use Survey\Domain\Entity\Entity;
use Survey\Domain\Validator\ValidatorInterface;
use Survey\Domain\ValueObject\SurveyResultAnswer;

class SurveyResultValidator implements ValidatorInterface
{
    public const CONTEXT = 'surveyResult';

    public function validate(Entity $entity): void
    {
        try {
            $validator = v::attribute(
                'surveyId',
                v::stringType()->length(1, 255)
            )
                ->attribute(
                    'question',
                    v::stringType()->length(1, 255)
                )
                ->attribute(
                    'answers',
                    v::arrayType()->each(v::instance(SurveyResultAnswer::class))
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
