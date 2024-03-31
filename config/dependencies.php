<?php

declare(strict_types=1);

use DI\Bridge\Slim\Bridge;
use League\Plates\Engine;
use Psr\Container\ContainerInterface;
use Slim\App;

return [
    App::class => function (ContainerInterface $container): App {
        $app = Bridge::create($container);
        $app->addBodyParsingMiddleware();
        $app->addRoutingMiddleware();
        $app->addErrorMiddleware(true, true, true);

        return $app;
    },
    Engine::class => function (): Engine {
        return new Engine(__DIR__ . '/../render', 'phtml');
    },
];
