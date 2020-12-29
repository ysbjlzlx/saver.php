<?php

namespace App\Action;

use Illuminate\Validation\Validator;
use League\Flysystem\Filesystem;
use Psr\Http\Message\ResponseInterface;

class StoreAction extends Action
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var \Illuminate\Validation\Validator
     */
    private $validator;

    public function __construct(Filesystem $filesystem, Validator $validator)
    {
        $this->filesystem = $filesystem;
        $this->validator = $validator;
    }

    protected function action(): ResponseInterface
    {
        $rules = [
            'extension' => 'required|string',
        ];
        $this->validator->setData($this->request->getQueryParams());
        $this->validator->addRules($rules);

        if ($this->validator->fails()) {
            return $this->response->withJson($this->validator->errors());
        }

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
