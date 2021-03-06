<?php

declare(strict_types=1);

use App\Event\UserLoginEvent;
use App\Handler\DatabaseHandler;
use App\Handler\LogPushHandler;
use App\Listener\LogUserLoginEventListener;
use DI\ContainerBuilder;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use League\Event\EventDispatcher;
use League\Flysystem\Filesystem;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        /*
         * Filesystem
         */
        Illuminate\Contracts\Filesystem\Filesystem::class => function (ContainerInterface $container): Illuminate\Filesystem\FilesystemAdapter {
            $adapter = new \League\Flysystem\Adapter\Local(DATA_DIR);
            $filesystem = new Filesystem($adapter);

            return new \Illuminate\Filesystem\FilesystemAdapter($filesystem);
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
        LoggerInterface::class => function (ContainerInterface $container): Logger {
            $name = 'default';
            $path = __DIR__.'/../vars/logs/'.$name.'.log';
            $rotatingFileHandler = new RotatingFileHandler($path, 30);
            $rotatingFileHandler->setFormatter(new JsonFormatter());
            $databaseHandler = new DatabaseHandler(Logger::NOTICE);
            $logger = new Logger($name);
            $logger->pushHandler(new LogPushHandler(Logger::ERROR));
            $logger->pushHandler($databaseHandler);
            $logger->pushHandler($rotatingFileHandler);
            $logger->pushProcessor(new UidProcessor(32));
            $logger->pushProcessor(new IntrospectionProcessor());

            return $logger;
        },
        /*
         * 事件
         */
        EventDispatcher::class => function (ContainerInterface $container): EventDispatcher {
            $logger = $container->get(LoggerInterface::class);
            $eventDispatcher = new EventDispatcher();
            $eventDispatcher->subscribeTo(UserLoginEvent::class, new LogUserLoginEventListener($logger));

            return $eventDispatcher;
        },
        CacheContract::class => function (ContainerInterface $container): CacheContract {
            $path = __DIR__.'/../vars/cache';
            $fileStore = new \Illuminate\Cache\FileStore(new \Illuminate\Filesystem\Filesystem(), $path);

            return new \Illuminate\Cache\Repository($fileStore);
        },
        /*
         * hash
         */
        \Illuminate\Contracts\Hashing\Hasher::class => function (ContainerInterface $container): Illuminate\Contracts\Hashing\Hasher {
            return new \Illuminate\Hashing\BcryptHasher();
        },
    ]);
};
