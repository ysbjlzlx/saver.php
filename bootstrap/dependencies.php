<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use League\Flysystem\Filesystem;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
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
         * Monolog 日志
         */
        Logger::class => function (ContainerInterface $container) {
            $name = 'default';
            $path = __DIR__.'/../vars/logs/'.$name.'-'.date('Ymd').'.log';
            $streamHandler = new StreamHandler($path);
            $streamHandler->setFormatter(new JsonFormatter());
            $logger = new Logger($name);
            $logger->pushHandler($streamHandler);
            $logger->pushProcessor(new UidProcessor(32));

            return $logger;
        },
    ]);
};
