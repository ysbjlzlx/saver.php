<?php

namespace App\Util;

use Illuminate\Contracts\Hashing\Hasher;

class HashUtil implements Hasher
{
    public function info($hashedValue)
    {
        return password_get_info($hashedValue);
    }

    public function make($value, array $options = [])
    {
        return password_hash($value, PASSWORD_BCRYPT, $options);
    }

    public function check($value, $hashedValue, array $options = [])
    {
        return password_verify($value, $hashedValue);
    }

    public function needsRehash($hashedValue, array $options = [])
    {
        return password_needs_rehash($hashedValue, PASSWORD_BCRYPT, $options);
    }
}
