<?php

use App\Action\DestroyAction;
use App\Action\ShowAction;
use App\Action\StoreAction;
use App\Action\UpdateAction;
use App\Exception\HttpErrorHandler;
use App\Exception\ShutdownHandler;
use App\ResponseEmitter\ResponseEmitter;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

require __DIR__.'/../vendor/autoload.php';

define('BASE_DIR', dirname(dirname(__FILE__)));
define('DATA_DIR', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'data');

$containerBuilder = new ContainerBuilder();

// Set up dependencies
$dependencies = require __DIR__.'/../bootstrap/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

$app->post('/api/store', StoreAction::class);
$app->delete('/api/destroy', DestroyAction::class);
$app->put('/api/update', UpdateAction::class);
$app->get('/api/show', ShowAction::class);

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, true);
register_shutdown_function($shutdownHandler);

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
