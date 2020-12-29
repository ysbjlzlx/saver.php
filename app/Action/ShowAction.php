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
            $mimeType = $this->filesystem->mimeType($key);
            if ('text/plain' === $mimeType) {
                $this->response->getBody()->write($this->filesystem->read($key));

                return $this->response->withStatus(200)->withHeader('Content-Type', 'application/json; charset=utf-8');
            }
        }

        return $this->response->withStatus(404);
    }
}
