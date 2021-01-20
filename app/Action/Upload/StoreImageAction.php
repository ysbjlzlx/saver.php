<?php

namespace App\Action\Upload;

use App\Action\Action;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Str;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use League\Flysystem\FilesystemException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\UploadedFile;

class StoreImageAction extends Action
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
     * @throws FilesystemException
     * @throws ValidationException
     */
    protected function action(): ResponseInterface
    {
        $uploadedFiles = $this->request->getUploadedFiles();
        if (empty($uploadedFiles)) {
            $validator = $this->validator->make([], []);
            $validator->errors()->add('file', '文件不能为空');
            throw new ValidationException($validator);
        }
        $filenames = $this->save($uploadedFiles);

        return $this->response->withJson($filenames);
    }

    /**
     * @param array<UploadedFile> $uploadedFiles
     *
     * @return array 文件名
     *
     * @throws FilesystemException
     */
    private function save(array $uploadedFiles): array
    {
        $filenames = [];
        foreach ($uploadedFiles as $file) {
            $filename = date('Ymd').'-'.Str::random(32);
            $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
            if (!empty($extension)) {
                $filename = $filename.'.'.$extension;
            }

            $this->filesystem->write($filename, $file->getStream()->getContents());
            $filenames[] = $filename;
        }

        return $filenames;
    }
}
