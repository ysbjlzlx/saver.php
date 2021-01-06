<?php

namespace App\Service;

use App\Model\UserModel;
use App\Model\UserTokenModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class UserTokenService
{
    /**
     * @param string $token token
     *
     * @return null
     */
    public function getUserByToken(string $token)
    {
        $userTokenModel = null;
        try {
            $userTokenModel = UserTokenModel::query()->where('token', $token)->firstOrFail();
            $userTokenModel->touch();

            return $userTokenModel->user();
        } catch (ModelNotFoundException $exception) {
            return null;
        }
    }

    /**
     * @param UserModel                           $userModel 用户
     * @param array{'ua'?: string, 'ip'?: string} $params    登录参数
     *
     * @return UserTokenModel 登录记录
     */
    public function store(UserModel $userModel, array $params): UserTokenModel
    {
        $userTokenModel = new UserTokenModel();
        $userTokenModel->user_id = $userTokenModel->id;
        $userTokenModel->token = $this->generateToken($userModel);
        $userTokenModel->ua = $params['ua'] ?? '';
        $userTokenModel->ip = $params['ip'] ?? '';
        $userTokenModel->save();

        return $userTokenModel;
    }

    /**
     * @param UserModel|null $userModel 用户
     *
     * @return string token
     */
    private function generateToken(UserModel $userModel = null): string
    {
        $id = is_null($userModel) ? '' : $userModel->id;
        $current = microtime(true);
        $randomStr = Str::random(64);

        return md5($current.$randomStr.$id);
    }
}
