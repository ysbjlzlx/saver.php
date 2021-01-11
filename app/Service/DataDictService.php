<?php

namespace App\Service;

use App\Model\DataDictModel;

class DataDictService
{
    public function store(array $data)
    {
        if ($this->exists($data['key'])) {
            return false;
        }
        $dataDictModel = new DataDictModel();
        $dataDictModel->name = $data['name'];
        $dataDictModel->key = $data['key'];
        $dataDictModel->value = $data['value'];
        $dataDictModel->memo = $data['memo'] ?? '';

        return $dataDictModel->save();
    }

    public function show(string $key)
    {
        return DataDictModel::query()->where('key', $key)->first();
    }

    public function update(int $id, array $data)
    {
        $row = DataDictModel::query()->find($id);
        if (is_null($row)) {
            return false;
        }
        if ($data['version'] != $row['version']) {
            return false;
        }
        $where = [
            'id' => $id,
            'version' => $data['version'],
        ];
        ++$data['version'];

        return DataDictModel::query()->where($where)->update($data);
    }

    /**
     * 根据 key 判断是否存在.
     *
     * @param string $key key
     */
    private function exists(string $key): bool
    {
        return DataDictModel::query()->where('key', $key)->exists();
    }
}
