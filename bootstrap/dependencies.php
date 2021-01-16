<?php

declare(strict_types=1);

use App\Event\UserLoginEvent;
use App\Listener\LogUserLoginEventListener;
use App\Unit\CacheUnit;
use DI\ContainerBuilder;
use Doctrine\Common\Cache\FilesystemCache;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use League\Event\EventDispatcher;
use League\Flysystem\Filesystem;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Twig\Environment;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        /*
         * Filesystem 文件存储
         */
        Filesystem::class => function (ContainerInterface $container) {
            $adapter = new League\Flysystem\Local\LocalFilesystemAdapter(DATA_DIR);

            return new League\Flysystem\Filesystem($adapter);
        },
        /*
         * Twig 模板渲染
         */
        Environment::class => function (ContainerInterface $container) {
            $loader = new Twig\Loader\FilesystemLoader(__DIR__.'/../templates');

            return new Twig\Environment($loader);
        },
        /*
         * Validator 输入校验
         */
        Factory::class => function (ContainerInterface $container) {
            $langPath = __DIR__.'/../vars/lang';
            $fileLoader = new FileLoader(new Illuminate\Filesystem\Filesystem(), $langPath);
            $translator = new Translator($fileLoader, 'zh_CN');

            return new Factory($translator);
        },
        /*
         * 日志
         */
        LoggerInterface::class => function (ContainerInterface $container) {
            $name = 'default';
            $path = __DIR__.'/../vars/logs/'.$name.'.log';
            $databaseHandler = new \App\Handler\DatabaseHandler();
            $rotatingFileHandler = new RotatingFileHandler($path, 30);
            $rotatingFileHandler->setFormatter(new JsonFormatter());
            $logger = new Logger($name);
            $logger->pushHandler($rotatingFileHandler);
            $logger->pushHandler($databaseHandler);
            $logger->pushProcessor(new UidProcessor(32));
            $logger->pushProcessor(new \Monolog\Processor\IntrospectionProcessor());

            return $logger;
        },
        /*
         * 缓存
         */
        CacheInterface::class => function (ContainerInterface $container) {
            return new CacheUnit(new FilesystemCache(__DIR__.'/../vars/cache'));
        },
        /*
         * 事件
         */
        EventDispatcher::class => function (ContainerInterface $container) {
            $logger = $container->get(LoggerInterface::class);
            $eventDispatcher = new EventDispatcher();
            $eventDispatcher->subscribeTo(UserLoginEvent::class, new LogUserLoginEventListener($logger));

            return $eventDispatcher;
        },
    ]);
};
