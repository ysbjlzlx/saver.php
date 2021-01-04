<?php

namespace App\Action\Auth;

use App\Action\Action;
use Psr\Http\Message\ResponseInterface;

class RegisterAction extends Action
{
    protected function action(): ResponseInterface
    {
        $rules = [
            'username' => 'required|string|between:3,16',
            'password' => 'required|string|min:6',
        ];
        $this->validator->validate($this->request->getParsedBody(), $rules);
    }
}
