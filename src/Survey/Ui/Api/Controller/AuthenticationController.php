<?php

declare(strict_types=1);

namespace Survey\Ui\Api\Controller;

use Survey\Ui\Api\Adapter\Http\HttpHelper;
use Survey\Ui\Api\Adapter\Http\HttpResponse;

class AuthenticationController implements ControllerInterface
{

    public function __invoke(object $request): HttpResponse
    {
        return HttpHelper::ok($request);
    }
}
