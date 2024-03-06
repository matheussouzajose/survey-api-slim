<?php

declare(strict_types=1);

namespace Survey\Domain\Repository;

interface SurveyResultRepositoryInterface
{
    public function save(string $surveyId, string $userId, string $answer, string $date): void;

    public function loadBySurveyId(string $surveyId, string $userId): ?array;
}
