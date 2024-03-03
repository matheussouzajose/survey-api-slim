<?php

declare(strict_types=1);

namespace Survey\Main\Decorator;

use Survey\Domain\Repository\LogRepositoryInterface;
use Survey\Ui\Api\Adapter\Http\HttpResponse;
use Survey\Ui\Api\Controller\ControllerInterface;

class LogControllerDecorator implements ControllerInterface
{
    public function __construct(
        protected ControllerInterface $controller,
        protected LogRepositoryInterface $logRepository
    ) {
    }

    public function __invoke(object $request): HttpResponse
    {
        $httpResponse = ($this->controller)($request);
        if ( $httpResponse->getStatusCode() === 500 ) {
            $this->logRepository->logError($httpResponse->getBody());
        }

        return $httpResponse;
    }
}
