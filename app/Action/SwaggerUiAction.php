<?php

namespace App\Action;

use Illuminate\Validation\Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class SwaggerUiAction extends Action
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(LoggerInterface $logger, Factory $validator, Environment $twig)
    {
        parent::__construct($logger, $validator);
        $this->twig = $twig;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function action(): ResponseInterface
    {
        $html = $this->twig->render('swagger-ui.html');
        $this->response->getBody()->write($html);

        return $this->response;
    }
}
