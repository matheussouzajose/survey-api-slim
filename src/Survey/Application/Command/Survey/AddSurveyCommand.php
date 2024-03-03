<?php

declare(strict_types=1);

namespace Survey\Application\Command\Survey;

class AddSurveyCommand
{
    public function __construct(protected string $question, public array $surveyAnswers)
    {
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function getSurveyAnswers(): array
    {
        return $this->surveyAnswers;
    }
}
