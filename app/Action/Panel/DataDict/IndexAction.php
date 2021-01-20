<?php

namespace App\Action\Panel\DataDict;

use App\Action\Action;
use App\Service\DataDictService;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class IndexAction extends Action
{
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
        $params = $this->validate();
        $limit = (int) $params['limit'] ?? 1;
        $offset = (int) $params['offset'] ?? 0;
        $data = $this->dataDictService->index($limit, $offset);

        return $this->response->withJson($data);
    }

    /**
     * @throws ValidationException
     */
    private function validate(): array
    {
        $data = $this->request->getQueryParams();
        $rules = [
            'limit' => 'int|min:1',
            'offset' => 'int|min:0',
            'condition' => 'array',
        ];

        return $this->validator->validate($data, $rules);
    }
}
