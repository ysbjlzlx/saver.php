<?php

namespace App\Service;

use App\Model\DataDictModel;
use Psr\SimpleCache\CacheInterface;

class DataDictService
{
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * 新增数据字典配置.
     *
     * @param array $data 配置
     *
     * @return bool
     */
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
        if ($this->cache->has($this->getCacheKey($key))) {
            return $this->cache->get($key);
        }
        $row = DataDictModel::query()->where('key', $key)->first();
        $this->cache->set($this->getCacheKey($key), $row);

        return $row;
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
        $this->cache->delete($this->getCacheKey($row['key']));

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

    private function getCacheKey(string $key)
    {
        return 'data_dict:key:'.$key;
    }
}
