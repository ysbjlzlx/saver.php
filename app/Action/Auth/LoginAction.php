<?php

namespace App\Action\Auth;

use App\Action\Action;
use App\Service\UserService;
use App\Service\UserTokenService;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;

class LoginAction extends Action
{
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var UserTokenService
     */
    private $userTokenService;

    public function __construct(Logger $logger, Factory $validator, UserService $userService, UserTokenService $userTokenService)
    {
        parent::__construct($logger, $validator);
        $this->userService = $userService;
        $this->userTokenService = $userTokenService;
    }

    /**
     * @return ResponseInterface 响应
     *
     * @throws ValidationException 前端校验
     */
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
        $loginParams = [
            'ua' => $this->request->getHeaderLine('User-Agent'),
            'ip' => $this->request->getHeaderLine('Client-Ip'),
        ];
        $userTokenModel = $this->userTokenService->store($user, $loginParams);
        $data = [
            'token' => $userTokenModel->token,
        ];

        return $this->response->withJson($data);
    }
}
