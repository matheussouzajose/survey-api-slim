<?php

declare(strict_types=1);

namespace Survey\Domain\Event;

interface EventInterface
{
    public function dateTimeOccurred(): \DateTimeInterface;

    public function eventData();
}
