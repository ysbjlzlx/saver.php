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
        return false;
    }

    public function getMultiple($keys, $default = null)
    {
        return false;
    }

    public function setMultiple($values, $ttl = null)
    {
        return false;
    }

    public function deleteMultiple($keys)
    {
        return false;
    }

    public function has($key)
    {
        return $this->instance->contains($key);
    }
}
