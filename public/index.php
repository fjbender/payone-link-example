<?php

use DI\Bridge\Slim\Bridge;
use DI\Container;
use Fbender\Payonelink\Controller\CreateLinkController;
use Fbender\Payonelink\Controller\MainController;

require __DIR__ . '/../vendor/autoload.php';

// DI Container
$container = new Container();
// Load dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$app = Bridge::create($container);
if ($_ENV['APPLICATION_MODE'] === 'dev') {
    $app->addErrorMiddleware(true, true, true);
}
$app->addRoutingMiddleware();

$app->get('/', [MainController::class, 'get']);
$app->post('/createLink', [CreateLinkController::class, 'post']);

$app->run();
