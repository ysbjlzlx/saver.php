<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;

class StoreAction extends Action
{
    protected function action(): ResponseInterface
    {
        $key = $this->args['key'];
        $path = $this->getFilePath($key);
        if (file_exists(BASE_DIR) && is_dir(BASE_DIR) && is_writable(BASE_DIR)) {
            file_put_contents($path, 'aa');
        }
        $this->response->withStatus(201);
    }
}
