<?php

namespace App\Action\DataDict;

use App\Action\Action;
use App\Service\DataDictService;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class StoreAction extends Action
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
     * @return ResponseInterface
     * @throws ValidationException
     */
    protected function action(): ResponseInterface
    {
        $data = $this->request->getParsedBody();
        $rules = [
           'name' => 'required|string',
           'key' => 'required|string',
           'value' => 'required',
           'memo' => 'required|string',
       ];
        $validator = $this->validator->make($data, $rules);
        $params = $validator->validate();
        if ($this->dataDictService->exists($params['key'])) {
            $validator->errors()->add('key', 'key 已存在');
            throw new ValidationException($validator);
        }

        $this->dataDictService->store($params);

        return $this->response->withStatus(201);
    }
}
