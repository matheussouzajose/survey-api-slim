<?php

declare(strict_types=1);

namespace Survey\Main\Factories\Controller\SurveyResult;

use Survey\Infrastructure\Validation\SurveyResult\SaveSurveyResultValidation;
use Survey\Main\Decorator\LogControllerDecorator;
use Survey\Main\Factories\Command\SurveyResult\SaveSurveyResultCommandHandlerFactory;
use Survey\Main\Factories\Repository\LogRepositoryFactory;
use Survey\Ui\Api\Controller\ControllerInterface;
use Survey\Ui\Api\Controller\SurveyResult\SaveSurveyResultController;

class SaveSurveyResultControllerFactory
{
    public static function create(): ControllerInterface
    {
        $controller = new SaveSurveyResultController(
            validation: new SaveSurveyResultValidation(),
            commandHandler: SaveSurveyResultCommandHandlerFactory::create()
        );

        return new LogControllerDecorator(controller: $controller, logRepository: LogRepositoryFactory::create());
    }
}
