<?php

declare(strict_types=1);

namespace Survey\Main\Factories\Repository;

use Survey\Infrastructure\Persistence\MongoDb\Repository\SurveyResultRepository;

class SurveyResultRepositoryFactory
{
    public static function create(): SurveyResultRepository
    {
        return new SurveyResultRepository();
    }
}
