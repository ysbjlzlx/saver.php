<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;

abstract class Action
{
    /**
     * @var ServerRequest
     */
    protected $request;
    /**
     * @var Response
     */
    protected $response;
    /**
     * @var array<mixed>
     */
    protected $args;

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
