<?php

namespace App\Action;

use League\Flysystem\Filesystem;
use Psr\Http\Message\ResponseInterface;

class StoreAction extends Action
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
        $parseBody = $this->request->getParsedBody();
        if (file_exists(BASE_DIR) && is_dir(BASE_DIR) && is_writable(BASE_DIR) && !is_null($parseBody)) {
            $fileName = $this->getRandomFileName();
            $this->filesystem->write($fileName, serialize($parseBody));

            return $this->response->withJson(['key' => $fileName], 201);
        }
    }

    private function getRandomFileName(string $prefix = ''): string
    {
        $date = date('Ymd');

        return $date.'-'.hash('md5', uniqid($prefix, true));
    }
}
