<?php

declare(strict_types=1);

namespace Survey\Main\Factories\Controller\Account;

use Survey\Infrastructure\Validation\SignUpValidation;
use Survey\Main\Decorator\LogControllerDecorator;
use Survey\Main\Factories\Command\Account\SignInCommandHandlerFactory;
use Survey\Main\Factories\Command\Account\SignUpCommandHandlerFactory;
use Survey\Main\Factories\Repository\LogRepositoryFactory;
use Survey\Ui\Api\Controller\Account\SignUpController;
use Survey\Ui\Api\Controller\ControllerInterface;

class SignUpControllerFactory
{
    public static function create(): ControllerInterface
    {
        $controller = new SignUpController(
            validation: new SignUpValidation(),
            commandHandler: SignUpCommandHandlerFactory::create(),
            signInCommandHandler: SignInCommandHandlerFactory::create()
        );

        return new LogControllerDecorator(controller: $controller, logRepository: LogRepositoryFactory::create());
    }
}
