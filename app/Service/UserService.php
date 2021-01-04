<?php

namespace App\Service;

use App\Model\UserModel;

class UserService
{
    /**
     * 判断用户名是否已存在.
     *
     * @param string $username username
     *
     * @return bool bool
     */
    public function existsByUsername(string $username): bool
    {
        return UserModel::query()->where('username', $username)->exists();
    }
}
