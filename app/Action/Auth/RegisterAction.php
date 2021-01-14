<?php

namespace App\Action\Auth;

use App\Action\Action;
use App\Model\UserModel;
use App\Service\UserService;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class RegisterAction extends Action
{
    /**
     * @var UserService 用户服务
     */
    private $userService;

    public function __construct(LoggerInterface $logger, Factory $validator)
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

        $validator = $this->validator->make($this->request->getParsedBody(), $rules);
        $params = $validator->validate();
        // 校验用户是否存在
        $exists = $this->userService->existsByUsername($params['username']);
        if ($exists) {
            $validator->errors()->add('username', '该用户名已经被注册');
            throw new ValidationException($validator);
        }
        $user = $this->userService->store($params['username'], $params['password']);

        return $this->response->withJson($this->formatter($user));
    }

    private function formatter(UserModel $userModel): array
    {
        return [
            'username' => $userModel->username,
        ];
    }
}
