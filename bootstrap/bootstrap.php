<?php

declare(strict_types=1);

use Dotenv\Dotenv;

/**
 * 加载配置.
 */
$dotenv = Dotenv::createImmutable(dirname(dirname(__FILE__)));
$dotenv->load();

/**
 * 初始化.
 */
$config = require __DIR__.'/../config/app.php';
date_default_timezone_set($config['timezone']);
