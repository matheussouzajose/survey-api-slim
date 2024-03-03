<?php

declare(strict_types=1);

namespace Survey\Main\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\App;

class NoCacheMiddleware
{
    public static function setup(App $app): void
    {
        $app->add(function (Request $request, RequestHandler $handler) {
            $response = $handler->handle($request);
            return $response
                ->withHeader('cache-control', 'no-store, no-cache, must-revalidate, proxy-revalidate')
                ->withHeader('pragma', 'no-cache')
                ->withHeader('expires', '0')
                ->withHeader('surrogate-control', 'no-store');
        });
    }
}
