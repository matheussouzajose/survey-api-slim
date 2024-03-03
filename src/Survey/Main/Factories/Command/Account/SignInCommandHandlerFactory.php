<?php

declare(strict_types=1);

namespace Survey\Main\Factories\Command\Account;

use Survey\Application\Command\Account\SignInCommandHandler;
use Survey\Infrastructure\Cryptography\JwtAdapter\JwtAdapter;
use Survey\Main\Factories\Repository\AccountRepositoryFactory;

class SignInCommandHandlerFactory
{
    public static function create(): SignInCommandHandler
    {
        return new SignInCommandHandler(
            accountRepository: AccountRepositoryFactory::create(),
            encrypter: new JwtAdapter(getenv('SECRET_JWT'))
        );
    }
}
