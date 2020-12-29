<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;
use League\Flysystem\Filesystem;
use Psr\Container\ContainerInterface;
use Twig\Environment;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        Filesystem::class => function (ContainerInterface $container) {
            $adapter = new League\Flysystem\Local\LocalFilesystemAdapter(DATA_DIR);

            return new League\Flysystem\Filesystem($adapter);
        },
        Environment::class => function (ContainerInterface $container) {
            $loader = new Twig\Loader\FilesystemLoader(__DIR__.'/../templates');

            return new Twig\Environment($loader);
        },
        Validator::class => function (ContainerInterface $container) {
            $langPath = __DIR__.'/../vars/lang';
            $fileLoader = new FileLoader(new Illuminate\Filesystem\Filesystem(), $langPath);
            $translator = new Translator($fileLoader, 'zh-CN');

            return new Factory($translator);
        },
    ]);
};
