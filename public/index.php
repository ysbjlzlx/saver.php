<?php

use App\Exception\HttpErrorHandler;
use App\Exception\ShutdownHandler;
use App\ResponseEmitter\ResponseEmitter;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

/*
 * assert 函数第二个参数将抛出一个异常
 * @see https://www.php.net/manual/zh/info.configuration.php#ini.assert.exception
 */
ini_set('assert.exception', '1');

require __DIR__.'/../vendor/autoload.php';

define('BASE_DIR', dirname(dirname(__FILE__)));
define('DATA_DIR', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'data');

require __DIR__.'/../bootstrap/bootstrap.php';

$containerBuilder = new ContainerBuilder();

$config = require __DIR__.'/../bootstrap/config.php';
$config($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__.'/../bootstrap/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();
AppFactory::setContainer($container);

$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

/**
 * 初始化数据库.
 */
$database = require __DIR__.'/../bootstrap/database.php';
$database($app);

/**
 * IP.
 */
$checkProxyHeaders = true;
$trustedProxies = ['10.0.0.1', '10.0.0.2'];
$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));

/**
 * 加载路由.
 */
$route = require __DIR__.'/../bootstrap/route.php';
$route($app);

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
