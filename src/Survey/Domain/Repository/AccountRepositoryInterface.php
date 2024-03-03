<?php

declare(strict_types=1);

namespace Survey\Domain\Repository;

use Survey\Domain\Entity\Account;

interface AccountRepositoryInterface
{
    public function checkByEmail(string $email): bool;

    public function add(Account $entity): Account;

    public function loadByEmail(string $email): ?Account;

    public function updateAccessToken(Account $entity): ?int;

    public function checkByToken(string $token): bool;
}
