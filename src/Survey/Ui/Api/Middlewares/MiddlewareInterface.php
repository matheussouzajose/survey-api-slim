<?php

namespace Survey\Ui\Api\Middlewares;

use Survey\Ui\Api\Adapter\Http\HttpResponse;

interface MiddlewareInterface
{
    public function __invoke(object $request): HttpResponse;
}
