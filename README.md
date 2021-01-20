# saver.php

## 基础组件

| 组件               | 接口                                                  | 实现                                               |
| ------------------ | ----------------------------------------------------- | -------------------------------------------------- |
| Hash               | vendor/illuminate/contracts/Hashing/Hasher.php        | vendor/illuminate/hashing/BcryptHasher.php         |
| Cache              | vendor/illuminate/contracts/Cache/Repository.php      | vendor/illuminate/cache/Repository.php             |
| Logger             | vendor/psr/log/Psr/Log/LoggerInterface.php            | vendor/monolog/monolog/src/Monolog/Logger.php      |
| Filesystem（todo） | vendor/illuminate/contracts/Filesystem/Filesystem.php | vendor/illuminate/filesystem/FilesystemAdapter.php |
| Event              | vendor/league/event/src/EventDispatcher.php           | vendor/league/event/src/EventDispatcher.php        |
| Validation（todo） | vendor/illuminate/validation/Factory.php              | vendor/illuminate/validation/Factory.php           |



