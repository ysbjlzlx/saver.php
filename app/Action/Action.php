<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class Action
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;
    /**
     * @var ResponseInterface
     */
    protected $response;
    /**
     * @var array
     */
    protected $args;

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        return $this->action();
    }

    abstract protected function action(): ResponseInterface;

    protected function respondJson(array $data, int $status = 200): ResponseInterface
    {
        $this->response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));

        return $this->response->withStatus($status);
    }

    protected function getFilePath(string $name): string
    {
        return DATA_DIR.DIRECTORY_SEPARATOR.$name;
    }
}
