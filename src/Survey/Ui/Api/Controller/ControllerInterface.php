<?php

declare(strict_types=1);

namespace Survey\Ui\Api\Controller;

use Survey\Ui\Api\Adapter\Http\HttpResponse;

interface ControllerInterface
{
    public function __invoke(object $request): HttpResponse;
}
