<?php

declare(strict_types=1);

namespace Survey\Domain\Event;

interface EventHandlerInterface
{
    public function handle(EventInterface $event): void;
}
