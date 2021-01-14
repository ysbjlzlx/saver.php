<?php

namespace App\Action\DataDict;

use App\Action\Action;
use App\Service\DataDictService;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class IndexAction extends Action
{
    /**
     * @var DataDictService
     */
    private $dataDictService;

    public function __construct(LoggerInterface $logger, Factory $validator, DataDictService $dataDictService)
    {
        parent::__construct($logger, $validator);
        $this->dataDictService = $dataDictService;
    }

    /**
     * @throws ValidationException
     */
    protected function action(): ResponseInterface
    {
        $data = $this->request->getQueryParams();
        $rules = [
            'limit' => 'int|min:1',
            'offset' => 'int|min:0',
        ];
        $params = $this->validator->validate($data, $rules);
        $limit = (int) ($params['limit'] ?? 20);
        $offset = (int) ($params['offset'] ?? 0);

        $data = $this->dataDictService->index($limit, $offset);

        return $this->response->withJson($data);
    }
}
