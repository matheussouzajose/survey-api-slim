<?php

declare(strict_types=1);

namespace Survey\Application\Command\SurveyResult;

class SaveSurveyResultCommand
{
    public function __construct(protected string $userId, protected string $surveyId, public string $answer)
    {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getSurveyId(): string
    {
        return $this->surveyId;
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }
}
