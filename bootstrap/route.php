<?php

declare(strict_types=1);

return function (Slim\App $app) {
    $app->any('/health', App\Action\HealthAction::class);
    $app->get('/swagger-ui', App\Action\SwaggerUiAction::class);

    /*
     * account
     */
    $app->group('', function (Slim\Routing\RouteCollectorProxy $group) {
        $group->post('/api/account/delete', App\Action\Account\DestroyAccountAction::class);
    })->add(App\Middleware\AuthMiddleware::class);

    /*
     * auth
     */
    $app->post('/api/auth/register', App\Action\Auth\RegisterAction::class);
    $app->post('/api/auth/login', App\Action\Auth\LoginAction::class);
    $app->post('/api/auth/logout', App\Action\Auth\LogoutAction::class)
        ->add(App\Middleware\AuthMiddleware::class);

    /*
     * home
     */
    $app->get('/api/home', App\Action\Home\HomeAction::class)
        ->add(App\Middleware\AuthMiddleware::class);

    /*
     * upload
     */
    $app->post('/api/upload/store', App\Action\Upload\StoreAction::class);
    $app->get('/api/upload/show', App\Action\Upload\ShowAction::class);

    /*
     * data dict
     */
    $app->get('/api/data-dict/index', App\Action\DataDict\IndexAction::class);
    $app->get('/api/data-dict/show', App\Action\DataDict\ShowAction::class);
    $app->post('/api/data-dict/store', App\Action\DataDict\StoreAction::class);
    $app->put('/api/data-dict/update', App\Action\DataDict\UpdateAction::class);
};
