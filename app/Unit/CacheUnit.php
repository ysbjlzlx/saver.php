<?php

namespace App\Unit;

use Doctrine\Common\Cache\CacheProvider;
use Psr\SimpleCache\CacheInterface;

class CacheUnit implements CacheInterface
{
    /**
     * @var CacheProvider
     */
    private $instance;

    public function __construct(CacheProvider $cacheProvider)
    {
        $this->instance = $cacheProvider;
    }

    public function get($key, $default = null)
    {
        return $this->instance->contains($key) ? $this->instance->fetch($key) : $default;
    }

    public function set($key, $value, $ttl = null)
    {
        return $this->instance->save($key, $value, $ttl);
    }

    public function delete($key)
    {
        return $this->instance->delete($key);
    }

    public function clear()
    {
        return $this->instance->deleteAll();
    }

    /**
     * @param iterable $keys    传数组类型
     * @param null     $default 默认值设置不起作用
     *
     * @return array|iterable|mixed[]
     */
    public function getMultiple($keys, $default = null)
    {
        return $this->instance->fetchMultiple((array) $keys);
    }

    public function setMultiple($values, $ttl = null)
    {
        return $this->instance->saveMultiple((array) $values, $ttl);
    }

    public function deleteMultiple($keys)
    {
        return $this->instance->deleteMultiple((array) $keys);
    }

    public function has($key)
    {
        return $this->instance->contains($key);
    }
}
