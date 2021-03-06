<?php

namespace App\Action\Auth;

use App\Action\Action;
use App\Service\UserTokenService;
use Illuminate\Validation\Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class LogoutAction extends Action
{
    /**
     * @var UserTokenService
     */
    private $userTokenService;

    public function __construct(LoggerInterface $logger, Factory $validator, UserTokenService $userTokenService)
    {
        parent::__construct($logger, $validator);
        $this->userTokenService = $userTokenService;
    }

    protected function action(): ResponseInterface
    {
        $token = $this->request->getAttribute('token');
        $user = $this->request->getAttribute('user');
        $this->userTokenService->destroy($user, $token);

        return $this->response->withStatus(200);
    }
}
