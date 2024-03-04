<?php

declare(strict_types=1);

namespace Survey\Application\Command\SurveyResult;

use Survey\Domain\Repository\SurveyResultRepositoryInterface;

class SaveSurveyResultCommandHandler
{
    public function __construct(protected SurveyResultRepositoryInterface $surveyAnswerRepository)
    {
    }

    public function __invoke(SaveSurveyResultCommand $command): array
    {

        $this->surveyAnswerRepository->save(
            surveyId: $command->getSurveyId(),
            userId: $command->getUserId(),
            answer: $command->getAnswer(),
            date: new \DateTime()
        );

        return $this->surveyAnswerRepository->loadBySurveyId(
            surveyId: $command->getSurveyId(),
            userId: $command->getUserId()
        );
    }
}
