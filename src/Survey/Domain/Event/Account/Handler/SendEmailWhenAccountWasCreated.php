<?php

declare(strict_types=1);

namespace Survey\Domain\Event\Account\Handler;

use Survey\Domain\Event\EventHandlerInterface;
use Survey\Domain\Event\EventInterface;

class SendEmailWhenAccountWasCreated implements EventHandlerInterface
{

    public function handle(EventInterface $event): void
    {

    }
}
