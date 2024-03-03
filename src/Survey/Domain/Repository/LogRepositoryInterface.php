<?php

declare(strict_types=1);

namespace Survey\Domain\Repository;

interface LogRepositoryInterface
{
    public function logError(array $errors): void;
}
