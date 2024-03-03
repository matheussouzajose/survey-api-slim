<?php

declare(strict_types=1);

namespace Survey\Main\Middlewares;

use Survey\Main\Adapters\SlimMiddlewareAdapter;
use Survey\Main\Factories\Middleware\AuthMiddlewareFactory;

class AuthMiddleware
{
    public function __invoke(): SlimMiddlewareAdapter
    {
        return new SlimMiddlewareAdapter(middleware: AuthMiddlewareFactory::create());
    }
}
