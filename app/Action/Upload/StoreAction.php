<?php

namespace App\Action\Upload;

use App\Action\Action;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use League\Flysystem\FileExistsException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class StoreAction extends Action
{
    /**
     * @phpstan-template FilesystemAdapter implements Filesystem
     *
     * @var FilesystemAdapter
     */
    private $filesystem;

    public function __construct(LoggerInterface $logger, Factory $validator, Filesystem $filesystem)
    {
        parent::__construct($logger, $validator);

        $this->filesystem = $filesystem;
    }

    /**
     * @throws ValidationException
     * @throws FileExistsException
     */
    protected function action(): ResponseInterface
    {
        $rules = [
            'type' => 'required|string|in:file,form',
            'extension' => 'required|string|in:json',
        ];
        $this->validator->validate($this->request->getQueryParams(), $rules);

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
