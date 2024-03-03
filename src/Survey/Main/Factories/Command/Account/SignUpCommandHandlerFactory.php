<?php

declare(strict_types=1);

namespace Survey\Main\Factories\Command\Account;

use Survey\Application\Command\SignUpCommandHandler;
use Survey\Main\Adapters\EventServiceAdapter;
use Survey\Main\Factories\Repository\AccountRepositoryFactory;

class SignUpCommandHandlerFactory
{
    public static function create(): SignUpCommandHandler
    {
        return new SignUpCommandHandler(
            accountRepository: AccountRepositoryFactory::create(),
            eventDispatcher: EventServiceAdapter::init()
        );
    }
}
