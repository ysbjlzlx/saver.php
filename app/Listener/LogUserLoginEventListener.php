<?php

namespace App\Listener;

use App\Event\UserLoginEvent;
use League\Event\Listener;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LogUserLoginEventListener implements Listener
{
    /**
     * @phpstan-template Logger implements LoggerInterface
     *
     * @var Logger
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param object $event event
     */
    public function __invoke(object $event): void
    {
        if (!$event instanceof UserLoginEvent) {
            return;
        }
        $user = $event->getUser();
        $this->logger->error('用户登录', $user->toArray());
    }
}
