<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Twig\Environment;

class CreateAction extends Action
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
        $body = $this->twig->render('profile.html', ['name' => 'a']);

        $this->response->getBody()->write($body);
        return $this->response;
    }
}
