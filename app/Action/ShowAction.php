<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;

class ShowAction extends Action
{
    protected function action(): ResponseInterface
    {
        $path = $this->getFilePath($this->args['key']);

        if (file_exists($path) && is_file($path) && is_readable($path)) {
            $contents = file_get_contents($path);
            $this->response->getBody()->write($contents);

            return $this->response->withHeader('Content-Type', 'application/json');
        }

        return $this->response->withStatus(404);
    }
}
