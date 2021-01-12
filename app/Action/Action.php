<?php

namespace App\Action;

use Illuminate\Validation\Factory;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

abstract class Action
{
    /**
     * @phpstan-template ServerRequest of ServerRequestInterface
     *
     * @var ServerRequest
     */
    protected $request;
    /**
     * @phpstan-template Response of ResponseInterface
     *
     * @var Response
     */
    protected $response;
    /**
     * @var array<mixed>
     */
    protected $args;
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var Factory
     */
    protected $validator;

    public function __construct(Logger $logger, Factory $validator)
    {
        $this->logger = $logger;
        $this->validator = $validator;
    }

    /**
     * @param ServerRequestInterface $request  请求
     * @param ResponseInterface      $response 响应
     * @param array<mixed>           $args     路径变量
     *
     * @return ResponseInterface 响应
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        return $this->action();
    }

    abstract protected function action(): ResponseInterface;

    protected function getFilePath(string $name): string
    {
        return DATA_DIR.DIRECTORY_SEPARATOR.$name;
    }
}
