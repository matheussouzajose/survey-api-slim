<?php

declare(strict_types=1);

namespace Survey\Main\Adapters;

use Survey\Domain\Event\EventDispatcher;

class EventServiceAdapter
{
    private static ?EventDispatcher $eventDispatcher = null;

    public static function init(): EventDispatcher {
        if (self::$eventDispatcher === null) {
            self::$eventDispatcher = new EventDispatcher();
        }
        return self::$eventDispatcher;
    }
}
