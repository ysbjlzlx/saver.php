<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Service\UserTokenService;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var Factory
     */
    private $validator;
    /**
     * @var UserTokenService
     */
    private $userTokenService;

    public function __construct(Factory $validator, UserTokenService $userTokenService)
    {
        $this->validator = $validator;
        $this->userTokenService = $userTokenService;
    }

    /**
     * @param ServerRequestInterface  $request 请求
     * @param RequestHandlerInterface $handler 请求回调
     *
     * @return ResponseInterface 响应
     *
     * @throws ValidationException 参数校验失败的异常
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = [];
        if ($request->isGet() | $request->isDelete()) {
            $data['token'] = $request->getQueryParam('token');
        } elseif ($request->isPost() | $request->isPut()) {
            $data['token'] = $request->getParsedBodyParam('token');
        }
        $rules = [
            'token' => 'required|string',
        ];
        $messages = [];
        $validator = $this->validator->make($data, $rules, $messages);
        $params = $validator->validate();
        $user = $this->userTokenService->getUserByToken($params['token']);
        if (is_null($user)) {
            $validator->errors()->add('token', 'Token is invalid');
            throw new ValidationException($validator);
        }
        $request = $request->withAttribute('user', $user);

        return $handler->handle($request);
    }
}
