<?php

namespace App\Exception;

use Illuminate\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Handlers\ErrorHandler;
use Slim\Http\Response;

class HttpErrorHandler extends ErrorHandler
{
    protected function respond(): ResponseInterface
    {
        /**
         * @var Response
         */
        $response = $this->responseFactory->createResponse();
        if ($this->exception instanceof HttpMethodNotAllowedException) {
            return $response->withStatus(405);
        }
        if ($this->exception instanceof ValidationException) {
            return $response->withJson($this->exception->errors(), 422);
        }

        return parent::respond();
    }
}
