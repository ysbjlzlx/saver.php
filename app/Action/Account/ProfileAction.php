<?php

namespace App\Action\Account;

use App\Action\Action;
use App\Model\UserModel;
use Psr\Http\Message\ResponseInterface;

class ProfileAction extends Action
{
    protected function action(): ResponseInterface
    {
        $user = $this->request->getAttribute('user');
        assert($user instanceof UserModel);

        return $this->response->withJson($user->toArray());
    }
}
