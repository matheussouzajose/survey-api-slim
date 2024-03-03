<?php

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Interfaces\ResponseInterface;
use Slim\Routing\RouteCollectorProxy;

return function (RouteCollectorProxy $app) {
    $app->get('/ping', function (ServerRequestInterface $request, ResponseInterface $response, $args) {
        $response->getBody()->write('pong');
        return $response->withStatus(200);
    });
};
