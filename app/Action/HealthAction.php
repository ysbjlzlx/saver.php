<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;

class HealthAction extends Action
{
    protected function action(): ResponseInterface
    {
        $data = [];

        return $this->response->withJson($data);
    }
}
