<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;

class StoreAction extends Action
{
    protected function action(): ResponseInterface
    {
        $parseBody = $this->request->getParsedBody();
        if (file_exists(BASE_DIR) && is_dir(BASE_DIR) && is_writable(BASE_DIR) && !is_null($parseBody)) {
            $fileName = $this->getRandomFileName();
            $path = $this->getFilePath($fileName);

            file_put_contents($path, serialize($parseBody));
            return $this->respondJson(['key'=>$fileName],201);
        }
    }

    private function getRandomFileName(): string
    {
        $date = date('Ymd');

        return $date.'-'.hash('md5', uniqid(true));
    }
}
