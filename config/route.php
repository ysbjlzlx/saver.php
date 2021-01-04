<?php

use App\Action\Auth\RegisterAction;
use App\Action\DestroyAction;
use App\Action\HealthAction;
use App\Action\ShowAction;
use App\Action\StoreAction;
use App\Action\SwaggerUiAction;
use App\Action\UpdateAction;
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
};
