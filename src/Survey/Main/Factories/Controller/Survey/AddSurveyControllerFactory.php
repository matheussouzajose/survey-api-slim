<?php

declare(strict_types=1);

namespace Survey\Main\Factories\Controller\Survey;

use Survey\Infrastructure\Validation\Survey\AddSurveyValidation;
use Survey\Main\Decorator\LogControllerDecorator;
use Survey\Main\Factories\Command\Survey\AddSurveyCommandHandlerFactory;
use Survey\Main\Factories\Repository\LogRepositoryFactory;
use Survey\Ui\Api\Controller\ControllerInterface;
use Survey\Ui\Api\Controller\Survey\AddSurveyController;

class AddSurveyControllerFactory
{
    public static function create(): ControllerInterface
    {
        $controller = new AddSurveyController(
            validation: new AddSurveyValidation(),
            commandHandler: AddSurveyCommandHandlerFactory::create()
        );

        return new LogControllerDecorator(controller: $controller, logRepository: LogRepositoryFactory::create());
    }
}
