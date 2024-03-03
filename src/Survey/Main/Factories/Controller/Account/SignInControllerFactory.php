<?php

declare(strict_types=1);

namespace Survey\Main\Factories\Controller\Account;

use Survey\Infrastructure\Validation\SignInValidation;
use Survey\Infrastructure\Validation\SignUpValidation;
use Survey\Main\Decorator\LogControllerDecorator;
use Survey\Main\Factories\Command\Account\SignInCommandHandlerFactory;
use Survey\Main\Factories\Command\Account\SignUpCommandHandlerFactory;
use Survey\Main\Factories\Repository\LogRepositoryFactory;
use Survey\Ui\Api\Controller\Account\SignInController;
use Survey\Ui\Api\Controller\Account\SignUpController;
use Survey\Ui\Api\Controller\ControllerInterface;

class SignInControllerFactory
{
    public static function create(): ControllerInterface
    {
        $controller =  new SignInController(
            validation: new SignInValidation(),
            commandHandler: SignInCommandHandlerFactory::create()
        );

        return new LogControllerDecorator(controller: $controller, logRepository: LogRepositoryFactory::create());
    }
}
