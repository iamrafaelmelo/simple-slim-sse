<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

$definitions = require __DIR__ . '/../config/dependencies.php';
$routes = require __DIR__ . '/../config/routes.php';

$builder = new ContainerBuilder();
$builder->addDefinitions($definitions);
$container = $builder->build();

/** @var App $app */
$app = $container->get(App::class);
$routes($app);

$app->run();
