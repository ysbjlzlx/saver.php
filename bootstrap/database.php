<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Slim\App;

return function (App $app) {
    /**
     * 初始化数据库.
     */
    $config = require __DIR__.'/../config/database.php';
    $capsule = new Capsule();
    $capsule->addConnection($config['mysql']);
    $capsule->bootEloquent();
};
