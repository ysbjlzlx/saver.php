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
        $extension = $this->request->getQueryParam('extension');
        $fileName = $this->getRandomFileName($extension);
        $content = $this->request->getBody();
        $this->filesystem->write($fileName, $content);

        return $this->response->withJson(['key' => $fileName], 201);
    }

    private function getRandomFileName(string $extension = null, string $prefix = ''): string
    {
        $date = date('Ymd');

        $filename = $date.'-'.hash('md5', uniqid($prefix, true));
        if (!is_null($extension)) {
            $filename = $filename.'.'.$extension;
        }

        return $filename;
    }
}
