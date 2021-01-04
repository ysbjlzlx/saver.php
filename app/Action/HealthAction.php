<?php

namespace App\Action;

use App\Model\UserModel;
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
            UserModel::query()->first();

            return true;
        } catch (QueryException $exception) {
            $this->logger->error('数据库暂无法使用', [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);

            return false;
        }
    }
}
