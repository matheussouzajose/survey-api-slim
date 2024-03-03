<?php

declare(strict_types=1);

namespace Survey\Main\Config;

use Survey\Domain\Event\Account\AccountCreatedEvent;
use Survey\Domain\Event\Account\Handler\SendEmailWhenAccountWasCreated;
use Survey\Main\Adapters\EventServiceAdapter;

class Events
{
    public static function setup(): void
    {
        EventServiceAdapter::init()->register(eventName: AccountCreatedEvent::class, eventHandler: new SendEmailWhenAccountWasCreated());
    }
}
