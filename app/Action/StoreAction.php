<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;

class StoreAction extends Action
{
    protected function action(): ResponseInterface
    {
        if (file_exists(BASE_DIR) && is_dir(BASE_DIR) && is_writable(BASE_DIR)) {
            $fileName = $this->getRandomFileName();
            $path = $this->getFilePath($fileName);
            $contents = $this->request->getParsedBody();
            var_dump($contents);
            file_put_contents($path, $this->request->getBody());
            echo $fileName;
        }

        return $this->response->withStatus(201);
    }

    private function getRandomFileName(): string
    {
        $date = date('Ymd');

        return $date.'-'.hash('md5', uniqid(true));
    }
}
