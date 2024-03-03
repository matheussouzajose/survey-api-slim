<?php

declare(strict_types=1);

namespace Survey\Ui\Api\Controller\Survey;

use Survey\Application\Command\Survey\AddSurveyCommand;
use Survey\Application\Command\Survey\AddSurveyCommandHandler;
use Survey\Ui\Api\Adapter\Http\HttpHelper;
use Survey\Ui\Api\Adapter\Http\HttpResponse;
use Survey\Ui\Api\Controller\ControllerInterface;
use Survey\Ui\Api\Validation\ValidationInterface;

class AddSurveyController implements ControllerInterface
{
    public function __construct(
        protected ValidationInterface $validation,
        protected AddSurveyCommandHandler $commandHandler
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
                return HttpHelper::badRequest(error: $result['message']);
            }

            return HttpHelper::created(data: $result['result']);
        } catch (\Throwable $e) {
            return HttpHelper::serverError(error: $e);
        }
    }

    private function createFromRequest(object $request): AddSurveyCommand
    {
        return new AddSurveyCommand(
            question: $request->question ?? '',
            surveyAnswers: $request->answers ?? [],
        );
    }
}
