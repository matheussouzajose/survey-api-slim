<?php

declare(strict_types=1);

namespace Survey\Ui\Api\Controller\SurveyResult;

use Survey\Domain\Repository\SurveyRepositoryInterface;
use Survey\Domain\Repository\SurveyResultRepositoryInterface;
use Survey\Ui\Api\Adapter\Http\HttpHelper;
use Survey\Ui\Api\Adapter\Http\HttpResponse;
use Survey\Ui\Api\Controller\ControllerInterface;

class LoadSurveyResultController implements ControllerInterface
{
    public function __construct(
        protected SurveyResultRepositoryInterface $surveyResultRepository,
        protected SurveyRepositoryInterface $surveyRepository,
    ) {
    }

    public function __invoke(object $request): HttpResponse
    {
        try {
            $exists = $this->surveyRepository->checkById(surveyId: $request->survey_id);
            if ( !$exists ) {
                return HttpHelper::badRequest(error: 'Survey not found');
            }

            $surveyResult = $this->surveyResultRepository->loadBySurveyId(
                surveyId: $request->survey_id,
                userId: $request->user_id
            );
            return $surveyResult ? HttpHelper::ok(data: $surveyResult) : HttpHelper::noContent();
        } catch (\Exception $e) {
            return HttpHelper::serverError(error: $e);
        }
    }
}
