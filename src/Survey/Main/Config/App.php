<?php

declare(strict_types=1);

namespace Survey\Main\Config;

use Slim\Factory\AppFactory;
use Survey\Domain\Event\Account\AccountCreatedEvent;
use Survey\Domain\Event\Account\Handler\SendEmailWhenAccountWasCreated;
use Survey\Main\Adapters\EventServiceAdapter;

class App
{
    public function __invoke(): void
    {
        $app = AppFactory::create();

        Middlewares::setup(app: $app);
        Routes::setup(app: $app);
        Events::setup();

        $this->registerEvents();

        $app->run();
    }

    private function registerEvents(): void
    {
    }
}
