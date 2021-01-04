<?php

namespace App\Action\Auth;

use App\Action\Action;
use App\Service\UserService;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;

class RegisterAction extends Action
{
    /**
     * @var UserService 用户服务
     */
    private $userService;

    public function __construct(Logger $logger, Factory $validator)
    {
        parent::__construct($logger, $validator);
        $this->userService = new UserService();
    }

    /**
     * @throws ValidationException 字段校验失败抛出
     */
    protected function action(): ResponseInterface
    {
        $rules = [
            'username' => 'required|string|between:3,16',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ];
        $params = $this->validator->validate($this->request->getParsedBody(), $rules);
        // 校验用户是否存在
        $exists = $this->userService->existsByUsername($params['username']);
        if ($exists) {
            throw ValidationException::withMessages(['username' => ['该用户名已经被注册']]);
        }
    }
}
