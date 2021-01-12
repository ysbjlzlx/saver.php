<?php

namespace App\Action\DataDict;

use App\Action\Action;
use App\Service\DataDictService;
use Illuminate\Validation\Factory;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;

class ShowAction extends Action
{
    /**
     * @var DataDictService
     */
    private $dataDictService;

    public function __construct(Logger $logger, Factory $validator, DataDictService $dataDictService)
    {
        parent::__construct($logger, $validator);
        $this->dataDictService = $dataDictService;
    }

    protected function action(): ResponseInterface
    {
        $data = $this->request->getQueryParams();
        $rules = [
            'id' => 'required|int',
        ];
        $params = $this->validator->validate($data, $rules);

        $row = $this->dataDictService->find((int) $params['id']);
        if (empty($row)) {
            return $this->response->withStatus(404);
        }

        return $this->response->withJson($row->toArray());
    }
}
