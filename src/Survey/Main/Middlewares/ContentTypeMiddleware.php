<?php

declare(strict_types=1);

namespace Survey\Main\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\App;

class ContentTypeMiddleware
{
    public static function setup(App $app): void
    {
        $app->add(function (Request $request, RequestHandler $handler) {
            $response = $handler->handle($request);
            return $response
                ->withHeader('Content-Type', 'application/json');
        });
    }
}
