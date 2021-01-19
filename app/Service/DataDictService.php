<?php

namespace App\Service;

use App\Model\DataDictModel;
use App\Unit\CacheUtil;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class DataDictService
{
    /**
     * @phpstan-template CacheUtil implements CacheInterface
     *
     * @var CacheUtil
     */
    private $cache;
    /**
     * @phpstan-template Logger implements LoggerInterface
     *
     * @var Logger
     */
    private $logger;

    public function __construct(CacheInterface $cache, LoggerInterface $logger)
    {
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * @param int $limit  获取数据量
     * @param int $offset 跳过数据量
     *
     * @return array 数据
     */
    public function index(int $limit = 20, $offset = 0): array
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
     * @return bool 添加结果
     */
    public function store(array $data): bool
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

    /**
     * @param int $id id
     *
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function find(int $id)
    {
        return DataDictModel::query()->find($id);
    }

    /**
     * 获取数据库中存储的内容.
     *
     * @param string $key key
     *
     * @return string 存储值
     *
     * @throws ModelNotFoundException key 不存在时抛出
     */
    public function getValue(string $key): string
    {
        return $this->cache->remember($key, 5 * 60, function () use ($key) {
            $row = DataDictModel::query()->where('key', $key)->firstOrFail();

            return $row->getAttribute('value');
        });
    }

    /**
     * @param int   $id   id
     * @param array $data 待更新数据
     *
     * @return false|int 影响行数
     *
     * @throws ModelNotFoundException id 未找到
     */
    public function update(int $id, array $data)
    {
        $row = DataDictModel::query()->findOrFail($id);
        if ($data['version'] != $row['version']) {
            return false;
        }
        $where = [
            'id' => $id,
            'version' => $data['version'],
        ];
        ++$data['version'];
        try {
            $this->cache->delete($this->getCacheKey($row['key']));
        } catch (InvalidArgumentException $exception) {
            $this->logger->info('cache key is invalid.', ['key' => $this->getCacheKey($row['key'])]);
        }

        return DataDictModel::query()->where($where)->update($data);
    }

    /**
     * 根据 key 判断是否存在.
     *
     * @param string $key key
     *
     * @return bool key 是否存在
     */
    public function exists(string $key): bool
    {
        return DataDictModel::query()->where('key', $key)->exists();
    }

    /**
     * @param int      $id      id
     * @param int|null $version version
     *
     * @return bool id 是否存在
     */
    public function existsById(int $id, ?int $version = null): bool
    {
        $where = [
            'id' => $id,
        ];
        if (!is_null($version)) {
            $where['version'] = $version;
        }

        return DataDictModel::query()->where($where)->exists();
    }

    /**
     * @param string $key key
     *
     * @return string 缓存 key
     */
    private function getCacheKey(string $key): string
    {
        return 'data_dict:key:'.$key;
    }
}
