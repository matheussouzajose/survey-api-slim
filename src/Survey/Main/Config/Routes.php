<?php

declare(strict_types=1);

namespace Survey\Main\Config;

use Slim\App;
class Routes
{
    public static function setup(App $app): void
    {
        $routesDirectory = __DIR__ . '/../Routes/Api';
        $routes = scandir($routesDirectory);

        foreach ($routes as $route) {
            if ($route !== '.' && $route !== '..' && is_file($routesDirectory . '/' . $route)) {
                $callback = require $routesDirectory . '/' . $route;
                if ($callback instanceof \Closure) {
                    $callback($app);
                }
            }
        }
    }
}
