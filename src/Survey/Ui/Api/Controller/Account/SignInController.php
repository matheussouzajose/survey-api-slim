<?php

declare(strict_types=1);

namespace Survey\Ui\Api\Controller\Account;

use Survey\Application\Command\SignInCommand;
use Survey\Application\Command\SignInCommandHandler;
use Survey\Ui\Api\Adapter\Http\HttpHelper;
use Survey\Ui\Api\Adapter\Http\HttpResponse;
use Survey\Ui\Api\Controller\ControllerInterface;
use Survey\Ui\Api\Validation\ValidationInterface;

class SignInController implements ControllerInterface
{
    public function __construct(
        protected ValidationInterface $validation,
        protected SignInCommandHandler $commandHandler
    ) {
    }

    public function __invoke(object $request): HttpResponse
    {
        try {
            $error = $this->validation->validate(input: $request);
            if ( $error ) {
                return HttpHelper::badRequest(error: $error);
            }

            $authentication = ($this->commandHandler)(command: $this->createFromRequest(request: $request));
            if ( !$authentication['success'] ) {
                return HttpHelper::unauthorized();
            }

            return HttpHelper::ok(data: $authentication['result']);
        } catch (\Throwable $e) {
            return HttpHelper::serverError(error: $e);
        }
    }

    private function createFromRequest(object $request): SignInCommand
    {
        return new SignInCommand(
            email: $request->email ?? '',
            password: $request->password ?? '',
        );
    }
}
