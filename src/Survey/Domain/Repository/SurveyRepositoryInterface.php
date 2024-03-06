<?php

declare(strict_types=1);

namespace Survey\Domain\Repository;

use Survey\Domain\Entity\Survey;

interface SurveyRepositoryInterface
{
    public function add(Survey $entity): Survey;

    public function loadAll(string $userId): array;

    public function loadAnswersBySurveyId(string $surveyId): array;

    public function checkById(string $surveyId): bool;
}
