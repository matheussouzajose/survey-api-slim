<?php

declare(strict_types=1);

namespace Survey\Main\Factories\Repository;

use Survey\Infrastructure\Persistence\MongoDb\Repository\SurveyRepository;

class SurveyRepositoryFactory
{
    public static function create(): SurveyRepository
    {
        return new SurveyRepository();
    }
}
