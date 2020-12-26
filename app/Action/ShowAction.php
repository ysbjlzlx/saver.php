<?php

namespace App\Action;

use Flight;

class ShowAction extends Action
{
    public function __invoke(string $name)
    {
        $path = $this->getFilePath($name);

        if (file_exists($path) && is_file($path) && is_readable($path)) {
            $contents = file_get_contents($path);
            Flight::json(json_decode($contents, JSON_UNESCAPED_UNICODE));
        }
        // Flight::halt(404);
    }

}
