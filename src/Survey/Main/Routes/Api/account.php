<?php

use Slim\Routing\RouteCollectorProxy;
use Survey\Main\Adapters\SlimMiddlewareAdapter;
use Survey\Main\Adapters\SlimRouteAdapter;
use Survey\Main\Factories\Controller\Account\SignInControllerFactory;
use Survey\Main\Factories\Controller\Account\SignUpControllerFactory;
use Survey\Main\Factories\Middleware\AuthMiddlewareFactory;
use Survey\Ui\Api\Controller\AuthenticationController;

return function (RouteCollectorProxy $app) {
    $prefix = 'api';
    $version = 'v1';

    $app->post("/{$prefix}/{$version}/sign-up", new SlimRouteAdapter(controller: SignUpControllerFactory::create()));
    $app->post("/{$prefix}/{$version}/sign-in", new SlimRouteAdapter(controller: SignInControllerFactory::create()));
    $app->post(
        "/{$prefix}/{$version}/authentication",
        new SlimRouteAdapter(controller: new AuthenticationController())
    )->add(
        middleware: new SlimMiddlewareAdapter(middleware: AuthMiddlewareFactory::create())
    );
};
