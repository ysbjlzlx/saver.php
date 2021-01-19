<?php

namespace App\Service\Ad;

use App\Model\Ad\AdPublisherModel;

class AdPublisherService
{
    /**
     * 添加流量主.
     */
    public function store()
    {
        // todo
    }

    /**
     * 流量主列表.
     *
     * @return array 结果集
     */
    public function index(int $limit = 1, int $offset = 0, array $search = null): array
    {
        $data = [
            'total' => 0,
            'rows' => [],
        ];
        $model = AdPublisherModel::query();
        $data['total'] = $model->count();
        $data['rows'] = $model->take($limit)->skip($offset)->get();

        return $data;
    }

    /**
     * 更新流量主.
     */
    public function update()
    {
        // todo
    }
}
