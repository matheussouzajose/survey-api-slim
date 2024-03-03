<?php

declare(strict_types=1);

namespace Survey\Main\Factories\Command\Survey;

use Survey\Application\Command\Survey\AddSurveyCommandHandler;
use Survey\Main\Factories\Repository\SurveyRepositoryFactory;

class AddSurveyCommandHandlerFactory
{
    public static function create(): AddSurveyCommandHandler
    {
        return new AddSurveyCommandHandler(
            surveyRepository: SurveyRepositoryFactory::create()
        );
    }
}
