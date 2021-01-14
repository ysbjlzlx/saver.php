<?php

namespace App\Action\DataDict;

use App\Action\Action;
use App\Service\DataDictService;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class UpdateAction extends Action
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
        $data = $this->request->getParsedBody();
        $rules = [
            'id' => 'required|int',
            'version' => 'required|int',
            'name' => 'string',
            'key' => 'string',
            'value' => 'string',
            'memo' => 'string',
        ];
        $validator = $this->validator->make($data, $rules);
        $params = $validator->validate();

        if (!$this->dataDictService->existsById($params['id'], $params['version'])) {
            $validator->errors()->add('id', '配置不存在或者已过期');
            throw new ValidationException($validator);
        }
        $this->dataDictService->update($params['id'], $params);

        return $this->response->withStatus(200);
    }
}
