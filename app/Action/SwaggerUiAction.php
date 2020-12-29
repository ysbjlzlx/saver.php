<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Twig\Environment;

class SwaggerUiAction extends Action
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    protected function action(): ResponseInterface
    {
        $html = $this->twig->render('swagger-ui.html');
        $this->response->getBody()->write($html);

        return $this->response;
    }
}