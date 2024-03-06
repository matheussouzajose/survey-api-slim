<?php

use Slim\Routing\RouteCollectorProxy;
use Survey\Main\Adapters\SlimRouteAdapter;
use Survey\Main\Factories\Controller\Survey\AddSurveyControllerFactory;
use Survey\Main\Factories\Controller\Survey\LoadSurveysControllerFactory;
use Survey\Main\Factories\Controller\SurveyResult\LoadSurveyResultControllerFactory;
use Survey\Main\Factories\Controller\SurveyResult\SaveSurveyResultControllerFactory;
use Survey\Main\Middlewares\AdminAuthMiddleware;
use Survey\Main\Middlewares\AuthMiddleware;

return function (RouteCollectorProxy $app) {
    $prefix = 'api';
    $version = 'v1';

    $app->put("/{$prefix}/{$version}/surveys/{survey_id}/results", new SlimRouteAdapter(controller: SaveSurveyResultControllerFactory::create()))
        ->add(middleware: (new AuthMiddleware)());
    $app->get("/{$prefix}/{$version}/surveys/{survey_id}/results", new SlimRouteAdapter(controller: LoadSurveyResultControllerFactory::create()))
        ->add(middleware: (new AuthMiddleware())());
};
