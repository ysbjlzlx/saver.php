<?php

use App\Action\DestroyAction;
use App\Action\ShowAction;
use App\Action\StoreAction;
use App\Action\UpdateAction;
use Slim\Factory\AppFactory;

require __DIR__.'/../vendor/autoload.php';

define('BASE_DIR', dirname(dirname(__FILE__)));
define('DATA_DIR', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'data');

$app = AppFactory::create();

$app->get('/{key}', ShowAction::class);
$app->post('/{key}', StoreAction::class);
$app->put('/{key}', UpdateAction::class);
$app->delete('/{key}', DestroyAction::class);

$app->run();
