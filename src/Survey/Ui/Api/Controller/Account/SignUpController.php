<?php

declare(strict_types=1);

namespace Survey\Ui\Api\Controller\Account;

use Survey\Application\Command\Account\SignInCommand;
use Survey\Application\Command\Account\SignInCommandHandler;
use Survey\Application\Command\Account\SignUpCommand;
use Survey\Application\Command\Account\SignUpCommandHandler;
use Survey\Ui\Api\Adapter\Http\HttpHelper;
use Survey\Ui\Api\Adapter\Http\HttpResponse;
use Survey\Ui\Api\Controller\ControllerInterface;
use Survey\Ui\Api\Validation\ValidationInterface;

class SignUpController implements ControllerInterface
{
    public function __construct(
        protected ValidationInterface $validation,
        protected SignUpCommandHandler $commandHandler,
        protected SignInCommandHandler $signInCommandHandler,
    ) {
    }

    public function __invoke(object $request): HttpResponse
    {
        try {
            $error = $this->validation->validate(input: $request);
            if ( $error ) {
                return HttpHelper::badRequest(error: $error);
            }

            $result = ($this->commandHandler)(command: $this->createFromRequest(request: $request));
            if ( !$result['success'] ) {
                return HttpHelper::forbiden(error: $result['message']);
            }

            $authentication = $this->signIn(email: $request->email, password: $request->password);

            return HttpHelper::created(data: $authentication['result']);
        } catch (\Throwable $e) {
            return HttpHelper::serverError(error: $e);
        }
    }

    private function createFromRequest(object $request): SignUpCommand
    {
        return new SignUpCommand(
            firstName: $request->first_name ?? '',
            lastName: $request->last_name ?? '',
            email: $request->email ?? '',
            password: $request->password ?? '',
            passwordConfirmation: $request->password_confirmation ?? '',
        );
    }

    private function signIn(string $email, string $password): array
    {
        return ($this->signInCommandHandler)(command: new SignInCommand(email: $email, password: $password));
    }
}
