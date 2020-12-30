<?php

namespace App\Action;

use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Psr\Http\Message\ResponseInterface;

class StoreAction extends Action
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Factory
     */
    private $validator;

    public function __construct(Factory $validator, Filesystem $filesystem)
    {
        $this->validator = $validator;
        $this->filesystem = $filesystem;
    }

    /**
     * @throws ValidationException
     * @throws FilesystemException
     */
    protected function action(): ResponseInterface
    {
        $rules = [
            'extension' => 'required|string',
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
