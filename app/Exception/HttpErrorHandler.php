<?php


namespace App\Exception;


use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Handlers\ErrorHandler;

class HttpErrorHandler extends ErrorHandler
{
    protected function respond(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();
        if ($this->exception instanceof HttpMethodNotAllowedException){
            return $response->withStatus(405);
        }
        return parent::respond();
    }
}
