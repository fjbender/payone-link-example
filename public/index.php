<?php

use DI\Bridge\Slim\Bridge;
use Fbender\Payonelink\Controller\LinkController;
use Fbender\Payonelink\Controller\MainController;
use Fbender\Payonelink\Controller\NotificationController;

require __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../bootstrap.php';

// $container is within bootstrap.php, my IDE still complains about this
$app = Bridge::create($container);
if (($_ENV['APPLICATION_MODE'] ?? null)  === 'dev') {
    $app->addErrorMiddleware(true, true, true);
}
$app->addRoutingMiddleware();

// All the routes
$app->get('/', [MainController::class, 'home']);
$app->get('/links/new', [MainController::class, 'createLinkForm']);
$app->post('/links', [LinkController::class, 'createLink']);
$app->get('/links', [LinkController::class, 'getLinks']);
$app->get('/links/{linkId}', [LinkController::class, 'getLink']);
$app->post('/notify', [NotificationController::class, 'notifyPost']);

$app->run();
