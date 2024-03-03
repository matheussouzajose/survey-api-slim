<?php

declare(strict_types=1);

namespace Survey\Ui\Api\Validation;

interface ValidationInterface
{
    public function validate(object $input): array;
}
