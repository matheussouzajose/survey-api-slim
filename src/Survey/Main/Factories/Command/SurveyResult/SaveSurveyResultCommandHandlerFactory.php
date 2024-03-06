<?php

declare(strict_types=1);

namespace Survey\Main\Factories\Command\SurveyResult;

use Survey\Application\Command\SurveyResult\SaveSurveyResultCommandHandler;
use Survey\Main\Factories\Repository\SurveyRepositoryFactory;
use Survey\Main\Factories\Repository\SurveyResultRepositoryFactory;

class SaveSurveyResultCommandHandlerFactory
{
    public static function create(): SaveSurveyResultCommandHandler
    {
        return new SaveSurveyResultCommandHandler(
            surveyResultRepository: SurveyResultRepositoryFactory::create(),
            surveyRepository: SurveyRepositoryFactory::create()
        );
    }
}
