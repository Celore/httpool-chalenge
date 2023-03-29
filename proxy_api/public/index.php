<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

#$app = \DI\Bridge\Slim\Bridge::create();

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();

// Register routes
require __DIR__ . '/../src/routes.php';

$app->run();