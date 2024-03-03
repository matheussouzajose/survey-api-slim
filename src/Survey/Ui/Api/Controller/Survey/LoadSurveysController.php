<?php

declare(strict_types=1);

namespace Survey\Ui\Api\Controller\Survey;

use Survey\Domain\Repository\SurveyRepositoryInterface;
use Survey\Ui\Api\Adapter\Http\HttpHelper;
use Survey\Ui\Api\Adapter\Http\HttpResponse;
use Survey\Ui\Api\Controller\ControllerInterface;

class LoadSurveysController implements ControllerInterface
{
    public function __construct(
        protected SurveyRepositoryInterface $surveyRepository
    ) {
    }

    public function __invoke(object $request): HttpResponse
    {
        try {
            $result = $this->surveyRepository->loadAll(userId: $request->user_id);
            return count($result) > 0 ? HttpHelper::ok(data: $result) : HttpHelper::noContent();
        } catch (\Throwable $e) {
            return HttpHelper::serverError(error: $e);
        }
    }
}
