<?php

namespace App\Service\Ad;

use App\Model\Ad\AdPublisherModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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
     * @return Builder[]|Collection
     */
    public function index(int $limit = 1, int $offset = 0, ?array $search = null)
    {
        $model = AdPublisherModel::query();

        return $model->take($limit)->skip($offset)->get();
    }

    /**
     * 更新流量主.
     */
    public function update()
    {
        // todo
    }
}
