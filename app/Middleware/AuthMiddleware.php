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
use Slim\Http\ServerRequest;

/**
 * Class AuthMiddleware.
 */
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
        // 主要是为了 PHPStorm 的自动提示
        assert($request instanceof ServerRequest);
        $data = [
            'token' => $request->getParam('token'),
        ];

        $rules = [
            'token' => 'required|string',
        ];
        $validator = $this->validator->make($data, $rules);
        $params = $validator->validate();
        $user = $this->userTokenService->getUserByToken($params['token']);
        if (is_null($user)) {
            $validator->errors()->add('token', 'Token is invalid');
            throw new ValidationException($validator);
        }
        $request = $request->withAttribute('token', $params['token']);
        $request = $request->withAttribute('user', $user);

        return $handler->handle($request);
    }
}
