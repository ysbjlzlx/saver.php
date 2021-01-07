<?php

namespace App\Action\Home;

use App\Action\Action;
use App\Model\UserModel;
use Psr\Http\Message\ResponseInterface;

class HomeAction extends Action
{
    protected function action(): ResponseInterface
    {
        /**
         * @var UserModel
         */
        $user = $this->request->getAttribute('user');

        return $this->response->withJson($user->toArray());
    }
}
