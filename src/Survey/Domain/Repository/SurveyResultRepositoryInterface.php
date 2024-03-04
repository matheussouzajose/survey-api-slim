<?php

declare(strict_types=1);

namespace Survey\Domain\Repository;

use Survey\Domain\Entity\SurveyResult;

interface SurveyResultRepositoryInterface
{
    public function save(string $surveyId, string $userId, string $answer, \DateTimeInterface $date): SurveyResult;

    public function loadBySurveyId(string $surveyId, string $userId): array;
}
