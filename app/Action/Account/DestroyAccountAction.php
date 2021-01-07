<?php

namespace App\Action\Account;

use App\Action\Action;
use App\Service\UserService;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;

/**
 * Class DestroyAccountAction.
 */
class DestroyAccountAction extends Action
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(Logger $logger, Factory $validator, UserService $userService)
    {
        parent::__construct($logger, $validator);
        $this->userService = $userService;
    }

    protected function action(): ResponseInterface
    {
        $data = [
            'password' => $this->request->getParsedBodyParam('password'),
        ];
        $rules = [
            'password' => 'required|string|min:6',
        ];
        $validator = $this->validator->make($data, $rules);
        $params = $validator->validate();
        /**
         * 检查密码
         */
        $userModel = $this->request->getAttribute('user');
        if (!$this->userService->checkPassword($userModel, $params['password'])) {
            $validator->errors()->add('password', '密码错误');
            throw new ValidationException($validator);
        }
        /**
         * 销户
         */
        $this->userService->forceDelete($userModel);

        return $this->response->withStatus(200);
    }
}
