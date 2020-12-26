<?php

namespace App\Action;

class Action
{
    protected function getFilePath(string $name): string
    {
        return DATA_DIR . DIRECTORY_SEPARATOR . $name;
    }
}
