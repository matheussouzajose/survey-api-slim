<?php

declare(strict_types=1);

namespace Survey\Main\Factories\Controller\Survey;

use Survey\Main\Decorator\LogControllerDecorator;
use Survey\Main\Factories\Repository\LogRepositoryFactory;
use Survey\Main\Factories\Repository\SurveyRepositoryFactory;
use Survey\Ui\Api\Controller\ControllerInterface;
use Survey\Ui\Api\Controller\Survey\LoadSurveysController;

class LoadSurveysControllerFactory
{
    public static function create(): ControllerInterface
    {
        $controller = new LoadSurveysController(
            surveyRepository: SurveyRepositoryFactory::create()
        );

        return new LogControllerDecorator(controller: $controller, logRepository: LogRepositoryFactory::create());
    }
}
