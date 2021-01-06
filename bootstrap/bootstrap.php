<?php

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * 加载配置
 */
$dotenv = Dotenv::createImmutable(dirname(dirname(__FILE__)));
$dotenv->load();

/**
 * 初始化数据库.
 */
$config = require __DIR__.'/../config/database.php';
$capsule = new Capsule();
$capsule->addConnection($config['mysql']);
$capsule->bootEloquent();
