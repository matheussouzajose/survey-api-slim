<?php

declare(strict_types=1);

namespace Survey\Domain\Event\Account;

use Survey\Domain\Entity\Account;
use Survey\Domain\Event\EventInterface;

class AccountCreatedEvent implements EventInterface
{
    public function __construct(protected Account $account)
    {
    }
    public function dateTimeOccurred(): \DateTimeInterface
    {
        return new \DateTime();
    }

    public function eventData(): Account
    {
        return $this->account;
    }
}
