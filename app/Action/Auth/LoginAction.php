<?php

namespace App\Action\Auth;

use App\Action\Action;
use App\Service\UserService;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;

class LoginAction extends Action
{
    private $userService;

    public function __construct(Logger $logger, Factory $validator, UserService $userService)
    {
        parent::__construct($logger, $validator);
        $this->userService = $userService;
    }

    protected function action(): ResponseInterface
    {
        $rules = [
            'username' => 'required|string|min:3',
            'password' => 'required|string|min:6',
        ];
        $messages = [];
        $validator = $this->validator->make($this->request->getParsedBody(), $rules, $messages);
        $params = $validator->validated();

        $user = $this->userService->getUserByUsernameAndPassword($params['username'], $params['password']);
        if (is_null($user)) {
            $validator->errors()->add('username', '用户名或密码错误');
            throw new ValidationException($validator);
        }

        return $this->response->withStatus(200);
    }
}
