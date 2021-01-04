<?php

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * 初始化数据库.
 */
$config = require __DIR__.'/../config/database.php';
$capsule = new Capsule();
$capsule->addConnection($config['mysql']);
$capsule->bootEloquent();