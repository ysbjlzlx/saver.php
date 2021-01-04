<?php

namespace App\Action;

use App\Model\UserModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
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
        } catch (ModelNotFoundException | QueryException $exception) {
            return false;
        }
    }
}
