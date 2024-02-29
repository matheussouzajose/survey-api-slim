<?php

declare(strict_types=1);

namespace Survey\Domain\Event;

class EventDispatcher implements EventDispatcherInterface
{
    /** @var EventHandlerInterface[] */
    protected array $eventsHandlers;

    public function getEventHandlers(): array
    {
        return $this->eventsHandlers;
    }

    public function notify(EventInterface $event): void
    {
        $eventName = get_class($event);
        if ( isset($this->eventsHandlers[$eventName]) ) {
            foreach ($this->eventsHandlers[$eventName] as $eventsHandler) {
                $eventsHandler->handle($event);
            }
        }
    }

    public function register(string $eventName, EventHandlerInterface $eventHandler): void
    {
        if ( !isset($this->eventsHandlers[$eventName]) ) {
            $this->eventsHandlers[$eventName] = [];
        }
        $this->eventsHandlers[$eventName][] = $eventHandler;
    }

    public function unregister(string $eventName, EventHandlerInterface $eventHandler): void
    {
        if ( isset($this->eventsHandlers[$eventName]) ) {
            if ( ($key = array_search($eventHandler, (array)$this->eventsHandlers[$eventName], true)) !== false ) {
                unset($this->eventsHandlers[$eventName][$key]);
            }
        }
    }

    public function unregisterAll(): void
    {
        $this->eventsHandlers = [];
    }
}
