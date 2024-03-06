<?php

declare(strict_types=1);

namespace Survey\Application\Command\SurveyResult;

use Survey\Application\Traits\ResponseTrait;
use Survey\Domain\Repository\SurveyRepositoryInterface;
use Survey\Domain\Repository\SurveyResultRepositoryInterface;

class SaveSurveyResultCommandHandler
{
    use ResponseTrait;
    public function __construct(
        protected SurveyResultRepositoryInterface $surveyResultRepository,
        protected SurveyRepositoryInterface $surveyRepository,
    ) {
    }

    public function __invoke(SaveSurveyResultCommand $command): array
    {
        $answers = $this->surveyRepository->loadAnswersBySurveyId(surveyId: $command->getSurveyId());
        if ( !$answers ) {
            return self::error('Not found answers for this survey');
        }

        if (!in_array($command->answer, $answers)) {
            return self::error('Invalid answer for this survey');
        }

        $this->surveyResultRepository->save(
            surveyId: $command->getSurveyId(),
            userId: $command->getUserId(),
            answer: $command->getAnswer(),
            date: (new \DateTime())->format('Y-m-d H:i:s')
        );

        return $this->surveyResultRepository->loadBySurveyId(
            surveyId: $command->getSurveyId(),
            userId: $command->getUserId()
        );
    }
}
