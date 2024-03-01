<?php

declare(strict_types=1);

namespace Tests\Unit\Survey\Domain\Event;

use Survey\Domain\Event\EventDispatcher;
use Survey\Domain\Event\EventHandlerInterface;
use Survey\Domain\Event\EventInterface;
use Tests\TestCase;
use Tests\Unit\Mocks\Event\EventMock;

class EventDispatcherUnitTest extends TestCase
{
    public function test_can_be_register_an_event_handler()
    {
        $eventDispatcher = new EventDispatcher();
        $eventHandler = \Mockery::spy(\stdClass::class, EventHandlerInterface::class);

        $eventDispatcher->register(EventInterface::class, $eventHandler);

        $this->assertCount(1, $eventDispatcher->getEventHandlers()[EventInterface::class]);
        $this->assertEquals($eventHandler, $eventDispatcher->getEventHandlers()[EventInterface::class][0]);
    }

    public function test_can_be_unregister_an_event_handler()
    {
        $eventDispatcher = new EventDispatcher();
        $eventHandler = \Mockery::spy(\stdClass::class, EventHandlerInterface::class);

        $eventDispatcher->register(EventInterface::class, $eventHandler);

        $this->assertCount(1, $eventDispatcher->getEventHandlers()[EventInterface::class]);

        $eventDispatcher->unregister(EventInterface::class, $eventHandler);

        $this->assertCount(0, $eventDispatcher->getEventHandlers()[EventInterface::class]);
    }

    public function test_can_be_unregister_all()
    {
        $eventDispatcher = new EventDispatcher();
        $eventHandler = \Mockery::spy(\stdClass::class, EventHandlerInterface::class);

        $eventDispatcher->register(EventInterface::class, $eventHandler);

        $this->assertCount(1, $eventDispatcher->getEventHandlers()[EventInterface::class]);

        $eventDispatcher->unregisterAll();

        $this->assertCount(0, $eventDispatcher->getEventHandlers());
    }

    public function test_can_be_notify_all_event_handlers()
    {
        $eventDispatcher = new EventDispatcher();
        $eventHandler = \Mockery::spy(\stdClass::class, EventHandlerInterface::class);

        $eventDispatcher->register(EventMock::class, $eventHandler);

        $this->assertCount(1, $eventDispatcher->getEventHandlers()[EventMock::class]);

        $event = new EventMock();

        $eventDispatcher->notify(event: $event);

        $eventHandler->shouldHaveReceived('handle')->once();
    }

    protected function tearDown(): void
    {
        \Mockery::close();

        parent::tearDown();
    }
}
