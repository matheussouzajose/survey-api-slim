<?php

declare(strict_types=1);

namespace Survey\Main\Config;

use Slim\App;
use Survey\Main\Middlewares\BodyParserMiddleware;
use Survey\Main\Middlewares\ContentTypeMiddleware;
use Survey\Main\Middlewares\CorsMiddleware;
use Survey\Main\Middlewares\ErrorMiddleware;

class Middlewares
{
    public static function setup(App $app): void
    {
        BodyParserMiddleware::setup(app: $app);
        ContentTypeMiddleware::setup(app: $app);
        CorsMiddleware::setup(app: $app);
        ErrorMiddleware::setup(app: $app);
    }
}
