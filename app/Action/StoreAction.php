<?php

namespace App\Action;

class StoreAction extends Action
{
    public function __invoke(string $name)
    {
        $path = $this->getFilePath($name);
        if (file_exists(BASE_DIR) && is_dir(BASE_DIR) && is_writable(BASE_DIR)) {
            file_put_contents($path, 'aa');
        }
        echo "OK";
    }

}
