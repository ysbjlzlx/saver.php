<?php

use App\Action\Account\DestroyAccountAction;
use App\Action\Auth\LoginAction;
use App\Action\Auth\LogoutAction;
use App\Action\Auth\RegisterAction;
use App\Action\HealthAction;
use App\Action\Home\HomeAction;
use App\Action\SwaggerUiAction;
use App\Action\Upload\ShowAction;
use App\Action\Upload\StoreAction;
use App\Middleware\AuthMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
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
    $app->get('/api/home', HomeAction::class)->add(AuthMiddleware::class);
    /*
     * upload
     */
    $app->post('/api/upload/store', StoreAction::class);
    $app->get('/api/upload/show', ShowAction::class);
    /*
     * data dict
     */
    $app->get('/api/data-dict/index', \App\Action\DataDict\IndexAction::class);
    $app->post('/api/data-dict/store', \App\Action\DataDict\StoreAction::class);
};
