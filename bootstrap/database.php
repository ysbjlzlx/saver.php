<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Slim\App;

return function (App $app) {
    /**
     * 初始化数据库.
     */
    $config_database = $app->getContainer()->get('config')['database'] ?? [];
    $capsule = new Capsule();
    $capsule->addConnection($config_database);
    $capsule->bootEloquent();
};
