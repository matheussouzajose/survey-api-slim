<?php

declare(strict_types=1);

namespace Survey\Main\Factories\Middleware;

use Survey\Infrastructure\Cryptography\JwtAdapter\JwtAdapter;
use Survey\Main\Factories\Repository\AccountRepositoryFactory;
use Survey\Ui\Api\Middlewares\AuthMiddleware;
use Survey\Ui\Api\Middlewares\MiddlewareInterface;

class AuthMiddlewareFactory
{
    public static function create(): MiddlewareInterface
    {
        return new AuthMiddleware(
            accountRepository: AccountRepositoryFactory::create(),
            encrypter: new JwtAdapter(
                getenv('SECRET_JWT')
            )
        );
    }
}
