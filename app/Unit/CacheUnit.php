<?php

namespace App\Unit;

use Closure;
use Doctrine\Common\Cache\CacheProvider;
use Illuminate\Contracts\Cache\Repository as CacheInterface;

/**
 * Class CacheUnit.
 */
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

    public function delete($key): bool
    {
        return $this->instance->delete($key);
    }

    public function clear(): bool
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

    public function pull($key, $default = null)
    {
        $value = $this->get($key, $default);
        $this->delete($key);

        return $value;
    }

    public function put($key, $value, $ttl = null)
    {
        return $this->set($key, $value, $ttl);
    }

    public function add($key, $value, $ttl = null)
    {
        if ($this->has($key)) {
            return false;
        }

        return $this->set($key, $value, $ttl);
    }

    public function increment($key, $value = 1)
    {
        $origin = $this->get($key, 0);
        if (is_numeric($origin)) {
            return $this->set($key, $origin + $value);
        }

        return false;
    }

    public function decrement($key, $value = 1)
    {
        $origin = $this->get($key, 0);
        if (is_numeric($origin)) {
            return $this->set($key, $origin - $value);
        }

        return false;
    }

    public function forever($key, $value)
    {
        return $this->set($key, $value);
    }

    public function remember($key, $ttl, Closure $callback)
    {
        if ($this->has($key)) {
            return $this->get($key);
        }
        $value = call_user_func($callback);
        $this->set($key, $value, $ttl);

        return $value;
    }

    public function sear($key, Closure $callback)
    {
        if ($this->has($key)) {
            return $this->get($key);
        }
        $value = call_user_func($callback);
        $this->set($key, $value);

        return $value;
    }

    public function rememberForever($key, Closure $callback)
    {
        if ($this->has($key)) {
            return $this->get($key);
        }
        $value = call_user_func($callback);
        $this->set($key, $value);

        return $value;
    }

    public function forget($key)
    {
        return $this->delete($key);
    }

    public function getStore()
    {
        // TODO: Implement getStore() method.
    }
}
