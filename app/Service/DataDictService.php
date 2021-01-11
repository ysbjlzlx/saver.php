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

    public function index(int $limit = 20, $offset = 0)
    {
        $model = DataDictModel::query();
        $total = $model->count();
        $rows = $model->take($limit)->skip($offset)->get();

        return ['total' => $total, 'rows' => $rows];
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
        $this->cache->set($this->getCacheKey($key), $row, 5 * 60);

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
    public function exists(string $key): bool
    {
        return DataDictModel::query()->where('key', $key)->exists();
    }

    public function existsById(int $id, ?int $version = null)
    {
        $where = [
            'id' => $id,
        ];
        if (!is_null($version)) {
            $where['version'] = $version;
        }

        return DataDictModel::query()->where($where)->exists();
    }

    private function getCacheKey(string $key)
    {
        return 'data_dict:key:'.$key;
    }
}
