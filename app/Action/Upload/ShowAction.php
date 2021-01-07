<?php

namespace App\Action\Upload;

use App\Action\Action;
use Illuminate\Validation\Factory;
use League\Flysystem\Filesystem;
use League\MimeTypeDetection\FinfoMimeTypeDetector;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;

class ShowAction extends Action
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var FinfoMimeTypeDetector
     */
    private $detector;

    public function __construct(Logger $logger, Factory $validator, Filesystem $filesystem)
    {
        parent::__construct($logger, $validator);
        $this->filesystem = $filesystem;
        $this->detector = new FinfoMimeTypeDetector();
    }

    protected function action(): ResponseInterface
    {
        $key = $this->request->getQueryParam('key');

        if ($this->filesystem->fileExists($key)) {
            $mimeType = $this->filesystem->mimeType($key);
            if ('text/plain' === $mimeType) {
                $this->response->getBody()->write($this->filesystem->read($key));
                $extension = $this->detector->detectMimeTypeFromPath($key) ?: $mimeType;

                return $this->response->withStatus(200)->withHeader('Content-Type', $extension);
            }
        }

        return $this->response->withStatus(404);
    }
}
