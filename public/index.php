<?php

use DI\Bridge\Slim\Bridge;
use Fbender\Payonelink\Controller\LinkController;
use Fbender\Payonelink\Controller\MainController;

require __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../bootstrap.php';

$app = Bridge::create($container);
if (($_ENV['APPLICATION_MODE'] ?? null)  === 'dev') {
    $app->addErrorMiddleware(true, true, true);
}
$app->addRoutingMiddleware();

$app->get('/', [MainController::class, 'home']);
$app->get('/links/new', [MainController::class, 'createLinkForm']);
$app->post('/links', [LinkController::class, 'createLink']);
$app->get('/links', [LinkController::class, 'listLinks']);
$app->get('/remote/links', [LinkController::class, 'getLinksRemote']);
$app->get('/links/{linkId}', [LinkController::class, 'getLink']);

$app->run();