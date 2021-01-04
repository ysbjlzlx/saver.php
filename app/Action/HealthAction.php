<?php

namespace App\Action;

use App\Model\UserModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Psr\Http\Message\ResponseInterface;

class HealthAction extends Action
{
    protected function action(): ResponseInterface
    {
        $data = [
            'database' => $this->checkDatabase(),
        ];

        return $this->response->withJson($data);
    }

    private function checkDatabase(): bool
    {
        try {
            UserModel::query()->firstOrFail();

            return true;
        } catch (ModelNotFoundException $exception) {
            return false;
        }
    }
}
