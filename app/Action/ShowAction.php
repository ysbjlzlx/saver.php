<?php

namespace App\Action;

use League\Flysystem\Filesystem;
use Psr\Http\Message\ResponseInterface;

class ShowAction extends Action
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    protected function action(): ResponseInterface
    {
        $key = $this->args['key'];

        if ($this->filesystem->fileExists($key)) {
            $contents = $this->filesystem->read($this->args['key']);
            $contents = unserialize($contents);

            return $this->respondJson($contents);
        }

        return $this->response->withStatus(404);
    }
}
