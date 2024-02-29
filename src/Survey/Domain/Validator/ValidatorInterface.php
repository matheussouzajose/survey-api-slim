<?php

namespace Survey\Domain\Validator;

use Survey\Domain\Entity\Entity;

interface ValidatorInterface
{
    public function validate(Entity $entity): void;
}
