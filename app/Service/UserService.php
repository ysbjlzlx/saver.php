<?php

namespace App\Service;

use App\Model\UserModel;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService
{
    /**
     * @var Hasher
     */
    private $hasher;

    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

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
        $user->password = $this->hasher->make($password);
        $user->save();

        return $user;
    }

    /**
     * @param string      $username 用户名
     * @param string|null $password 密码
     * @param string|null $otp      otp
     *
     * @return Builder|Model|UserModel|null
     */
    public function getUserByUsername(string $username, ?string $password = null, ?string $otp = null)
    {
        $user = null;
        try {
            $user = UserModel::query()->where('username', $username)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return null;
        }
        // 校验密码
        if (!empty($password) && !$this->checkPassword($user, $password)) {
            return null;
        }
        // 校验 otp
        if (!empty($user->totp_secret) && (empty($otp) || !(new TOTPService($user->totp_secret))->verify($otp))) {
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

    /**
     * @param UserModel|Model $userModel 用户
     * @param string          $password  密码
     *
     * @return bool true 通过
     */
    public function checkPassword(UserModel $userModel, string $password): bool
    {
        return $this->hasher->check($password, $userModel->password);
    }

    /**
     * 用户销户.
     *
     * @throws \Exception
     */
    public function forceDelete(UserModel $userModel): bool
    {
        $userModel->tokens()->forceDelete();
        $userModel->delete();

        return true;
    }
}
