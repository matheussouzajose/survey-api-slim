<?php

declare(strict_types=1);

namespace Tests\Unit\Mocks\Event;

use Survey\Domain\Event\EventInterface;

class EventMock implements EventInterface
{
    protected \DateTimeInterface $dateTime;

    public function __construct()
    {
        $this->dateTime = new \DateTime();
    }

    public function dateTimeOccurred(): \DateTimeInterface
    {
        return $this->dateTime;
    }

    public function eventData()
    {
        // TODO: Implement eventData() method.
    }
}
