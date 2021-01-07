<?php

namespace App\Action;

use App\Model\UserModel;
use Psr\Http\Message\ResponseInterface;

class HomeAction extends Action
{
    protected function action(): ResponseInterface
    {
        $this->logger->info('header', $this->request->getHeaders());
        $this->logger->info('server', $_SERVER);
        /**
         * @var UserModel
         */
        $user = $this->request->getAttribute('user');

        return $this->response->withJson($user->toArray());
    }
}
