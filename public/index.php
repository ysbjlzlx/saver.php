<?php

use App\Action\DestroyAction;
use App\Action\ShowAction;
use App\Action\StoreAction;
use App\Action\UpdateAction;

require __DIR__ . '/../vendor/autoload.php';

define('BASE_DIR', dirname(dirname(__FILE__)));
define('DATA_DIR', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'data');

Flight::route('GET /@name', [new ShowAction(), '__invoke']);
Flight::route('POST /@name', [new StoreAction(), '__invoke']);
Flight::route('PUT /@name', [new UpdateAction, '__invoke']);
Flight::route('DELETE /@name', [new DestroyAction(), '__invoke']);

Flight::start();
