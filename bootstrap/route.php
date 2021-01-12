<?php

use App\Action\Account\DestroyAccountAction;
use App\Action\Auth\LoginAction;
use App\Action\Auth\LogoutAction;
use App\Action\Auth\RegisterAction;
use App\Action\HealthAction;
use App\Action\SwaggerUiAction;
use App\Middleware\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

return function (Slim\App $app) {
    $app->any('/health', HealthAction::class);
    $app->get('/swagger-ui', SwaggerUiAction::class);

    /*
     * account
     */
    $app->group('', function (RouteCollectorProxy $group) {
        $group->post('/api/account/delete', DestroyAccountAction::class);
    })->add(AuthMiddleware::class);
    /*
     * auth
     */
    $app->post('/api/auth/register', RegisterAction::class);
    $app->post('/api/auth/login', LoginAction::class);
    $app->post('/api/auth/logout', LogoutAction::class)->add(AuthMiddleware::class);
    /*
     * home
     */
    $app->get('/api/home', App\Action\Home\HomeAction::class)->add(AuthMiddleware::class);
    /*
     * upload
     */
    $app->post('/api/upload/store', \App\Action\Upload\StoreAction::class);
    $app->get('/api/upload/show', \App\Action\Upload\ShowAction::class);
    /*
     * data dict
     */
    $app->get('/api/data-dict/index', App\Action\DataDict\IndexAction::class);
    $app->get('/api/data-dict/show', \App\Action\DataDict\ShowAction::class);
    $app->post('/api/data-dict/store', \App\Action\DataDict\StoreAction::class);
    $app->put('/api/data-dict/update', \App\Action\DataDict\UpdateAction::class);
};
