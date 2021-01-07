<?php

declare(strict_types=1);

use DI\ContainerBuilder;

$config_app = require __DIR__.'/../config/app.php';
$config_database = require __DIR__.'/../config/database.php';

return function (ContainerBuilder $containerBuilder) use ($config_app, $config_database) {
    $containerBuilder->addDefinitions([
        'config' => [
            'app' => $config_app,
            'database' => $config_database,
        ],
    ]);
};
