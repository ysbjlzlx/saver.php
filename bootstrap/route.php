<?php

use App\Action\Auth\LoginAction;
use App\Action\Auth\LogoutAction;
use App\Action\Auth\RegisterAction;
use App\Action\DestroyAction;
use App\Action\HealthAction;
use App\Action\Home\HomeAction;
use App\Action\ShowAction;
use App\Action\StoreAction;
use App\Action\SwaggerUiAction;
use App\Action\UpdateAction;
use App\Middleware\AuthMiddleware;
use Slim\App;

return function (App $app) {
    $app->any('/health', HealthAction::class);
    $app->get('/swagger-ui', SwaggerUiAction::class);
    $app->post('/api/store', StoreAction::class);
    $app->delete('/api/destroy', DestroyAction::class);
    $app->put('/api/update', UpdateAction::class);
    $app->get('/api/show', ShowAction::class);
    // auth
    $app->post('/api/auth/register', RegisterAction::class);
    $app->post('/api/auth/login', LoginAction::class);
    $app->post('/api/auth/logout', LogoutAction::class)->add(AuthMiddleware::class);
    // home
    $app->get('/api/home', HomeAction::class)->add(AuthMiddleware::class);
};
