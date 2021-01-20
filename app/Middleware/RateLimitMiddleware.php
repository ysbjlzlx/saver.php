<?php

namespace App\Middleware;

use Illuminate\Cache\Repository;
use Illuminate\Contracts\Cache\Repository as CacheContract;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class RateLimitMiddleware.
 * 限流中间件.
 *
 * @see https://pipedrive.readme.io/docs/core-api-concepts-rate-limiting
 */
class RateLimitMiddleware implements MiddlewareInterface
{
    /**
     * @phpstan-template Repository implements CacheContract
     *
     * @var Repository
     */
    private $cache;
    /**
     * @phpstan-template Logger implements LoggerInterface
     *
     * @var Logger
     */
    private $logger;

    public function __construct(CacheContract $cache, LoggerInterface $logger)
    {
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request);
    }
}
