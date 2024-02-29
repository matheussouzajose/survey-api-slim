<?php

declare(strict_types=1);

namespace Survey\Domain\Event;

interface EventDispatcherInterface
{
    public function notify(EventInterface $event): void;

    public function register(string $eventName, EventHandlerInterface $eventHandler): void;

    public function unregister(string $eventName, EventHandlerInterface $eventHandler): void;

    public function unregisterAll(): void;
}
