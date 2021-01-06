<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;

class HomeAction extends Action
{
    protected function action(): ResponseInterface
    {
        return $this->response->withJson(['a' => 'a']);
    }
}
