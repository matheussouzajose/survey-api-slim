<?php

declare(strict_types=1);

namespace Survey\Application\Command\Survey;

use Survey\Application\Traits\ResponseTrait;
use Survey\Domain\Entity\Survey;
use Survey\Domain\Exception\NotificationErrorException;
use Survey\Domain\Repository\SurveyRepositoryInterface;
use Survey\Domain\ValueObject\Image;
use Survey\Domain\ValueObject\SurveyAnswer;

class AddSurveyCommandHandler
{
    use ResponseTrait;

    public function __construct(protected SurveyRepositoryInterface $surveyRepository)
    {
    }

    /**
     * @throws NotificationErrorException
     */
    public function __invoke(AddSurveyCommand $command): array
    {
        $survey = new Survey(question: $command->getQuestion());

        if ( count($command->getSurveyAnswers()) === 0 ) {
            return self::error('Answers is required');
        }

        $canAddSurvey = $this->addSurveyAnswer(surveyAnswers: $command->getSurveyAnswers(), survey: $survey);
        if ( !$canAddSurvey ) {
            return self::error('Answers is required');
        }

        $result = $this->surveyRepository->add(entity: $survey);

        return self::success(message: 'Survey created successfully', body: $result->toArray());
    }

    /**
     * @throws NotificationErrorException
     */
    private function addSurveyAnswer(array $surveyAnswers, Survey $survey): bool
    {
        foreach ($surveyAnswers as $surveyAnswer) {
            if ( !isset($surveyAnswer['answer']) ) {
                return false;
            }

            $survey->addSurveyAnswer(
                surveyAnswer: new SurveyAnswer(
                    answer: $surveyAnswer['answer'],
                    image: isset($surveyAnswer['image']) ? new Image($surveyAnswer['image']) : null
                )
            );
        }

        return true;
    }
}
