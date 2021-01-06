<?php

namespace App\Service;

use App\Model\UserModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    /**
     * 添加用户.
     *
     * @param string $username 用户名
     * @param string $password 密码
     *
     * @return UserModel 用户
     */
    public function store(string $username, string $password): UserModel
    {
        $user = new UserModel();
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_BCRYPT);
        $user->save();

        return $user;
    }

    /**
     * @param string      $username 用户名
     * @param string|null $password 密码
     *
     * @return Builder|Model|null|UserModel
     */
    public function getUserByUsernameAndPassword(string $username, string $password = null)
    {
        $user = null;
        try {
            $user = UserModel::query()->where('username', $username)->firstOrFail();
            if (!is_null($password) && !password_verify($password, $user->password)) {
                $user = null;
            }
        } catch (ModelNotFoundException $exception) {
            return null;
        }

        return $user;
    }

    /**
     * @param int $id 用户 ID
     *
     * @return Builder|Builder[]|Collection|Model|null 用户
     */
    public function getUserById(int $id)
    {
        try {
            return UserModel::query()->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return null;
        }
    }
}
