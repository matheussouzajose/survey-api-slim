<?php

declare(strict_types=1);

namespace Tests\Unit\Mocks\Event;

use Survey\Domain\Event\EventDispatcherInterface;
use Survey\Domain\Event\EventHandlerInterface;
use Survey\Domain\Event\EventInterface;

class EventDispatcherMock implements EventDispatcherInterface
{
    public function notify(EventInterface $event): void
    {
        // TODO: Implement notify() method.
    }

    public function register(string $eventName, EventHandlerInterface $eventHandler): void
    {
        // TODO: Implement register() method.
    }

    public function unregister(string $eventName, EventHandlerInterface $eventHandler): void
    {
        // TODO: Implement unregister() method.
    }

    public function unregisterAll(): void
    {
        // TODO: Implement unregisterAll() method.
    }
}
