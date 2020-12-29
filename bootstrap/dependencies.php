<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use League\Flysystem\Filesystem;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        Filesystem::class => function (ContainerInterface $container) {
            $adapter = new League\Flysystem\Local\LocalFilesystemAdapter(DATA_DIR);

            return new League\Flysystem\Filesystem($adapter);
        },
    ]);
};
