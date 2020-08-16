<?php

use DI\Bridge\Slim\Bridge;
use DI\Container;
use Fbender\Payonelink\Controller\LinkController;
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

$app->get('/', [MainController::class, 'home']);
$app->get('/links/new', [MainController::class, 'createLinkForm']);
$app->post('/links', [LinkController::class, 'createLink']);
$app->get('/links', [LinkController::class, 'listLinks']);

$app->run();
