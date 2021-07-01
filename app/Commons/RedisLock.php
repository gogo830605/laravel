<?php

namespace App\Commons;

use Illuminate\Support\Facades\Redis;

class RedisLock
{
    public function lock(string $value)
    {

        $redis = Redis::connection();
        $key = 'redis_lock';

        if (is_null($redis->get($key))) {
            $redis->set($key, $value, 'EX', 2, 'NX');
        } else {
            return false;
        }

        return true;
    }

    public function unlock(string $value)
    {
        $redis = Redis::connection();
        $key = 'redis_lock';
        if ($redis->get($key) === $value) {
            $redis->del([$key]);
            return true;
        }

        return false;
    }
}
