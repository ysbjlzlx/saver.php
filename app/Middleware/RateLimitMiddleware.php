<?php

namespace App\Middleware;

use App\Util\CacheUtil;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Class RateLimitMiddleware.
 * 限流中间件.
 *
 * @see https://pipedrive.readme.io/docs/core-api-concepts-rate-limiting
 */
class RateLimitMiddleware implements MiddlewareInterface
{
    /**
     * @phpstan-template CacheUtil implements CacheInterface
     *
     * @var CacheUtil
     */
    private $cache;
    /**
     * @phpstan-template Logger implements LoggerInterface
     *
     * @var Logger
     */
    private $logger;

    public function __construct(CacheInterface $cache, LoggerInterface $logger)
    {
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request);
    }
}
