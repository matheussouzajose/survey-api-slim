<?php

declare(strict_types=1);

namespace Survey\Main\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Response;

class ErrorMiddleware
{
    public static function setup(App $app): void
    {
        $errorMiddleware = $app->addErrorMiddleware(true, true, true);

        $errorMiddleware->setErrorHandler(
            [
                HttpNotFoundException::class,
                HttpMethodNotAllowedException::class
            ],
            function (ServerRequestInterface $request, \Throwable $exception, bool $displayErrorDetails) {
                $response = new Response();
                $response->getBody()->write(json_encode(['error' => $exception->getMessage()]));
                return $response->withStatus($exception->getCode())->withHeader('Content-Type', 'application/json');
            }
        );
    }
}
