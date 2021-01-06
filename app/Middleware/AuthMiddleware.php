<?php

declare(strict_types=1);

namespace App\Middleware;

use Illuminate\Validation\Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var Factory
     */
    private static $validator;

    public function __construct(Factory $validator)
    {
        self::$validator = $validator;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = [];
        $rules = [
            'token' => 'required',
        ];
        $messages = [];
        self::$validator->validate($data, $rules, $messages);

        return $handler->handle($request);
    }
}
