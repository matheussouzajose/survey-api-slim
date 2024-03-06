<?php

declare(strict_types=1);

namespace Survey\Main\Factories\Controller\SurveyResult;

use Survey\Main\Factories\Repository\SurveyRepositoryFactory;
use Survey\Main\Factories\Repository\SurveyResultRepositoryFactory;
use Survey\Ui\Api\Controller\SurveyResult\LoadSurveyResultController;

class LoadSurveyResultControllerFactory
{
    public static function create(): LoadSurveyResultController
    {
        return new LoadSurveyResultController(
            surveyResultRepository: SurveyResultRepositoryFactory::create(),
            surveyRepository: SurveyRepositoryFactory::create()
        );
    }
}
