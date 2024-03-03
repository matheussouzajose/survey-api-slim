<?php

use Slim\Routing\RouteCollectorProxy;
use Survey\Main\Adapters\SlimRouteAdapter;
use Survey\Main\Factories\Controller\Survey\AddSurveyControllerFactory;
use Survey\Main\Factories\Controller\Survey\LoadSurveysControllerFactory;
use Survey\Main\Middlewares\AdminAuthMiddleware;
use Survey\Main\Middlewares\AuthMiddleware;

return function (RouteCollectorProxy $app) {
    $prefix = 'api';
    $version = 'v1';

    $app->post("/{$prefix}/{$version}/surveys", new SlimRouteAdapter(controller: AddSurveyControllerFactory::create()))
        ->add(middleware: (new AdminAuthMiddleware)());
    $app->get("/{$prefix}/{$version}/surveys", new SlimRouteAdapter(controller: LoadSurveysControllerFactory::create()))
        ->add(middleware: (new AuthMiddleware())());
};
