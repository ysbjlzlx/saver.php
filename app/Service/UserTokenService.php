<?php

namespace App\Service;

use App\Model\UserModel;
use App\Model\UserTokenModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Monolog\Logger;

class UserTokenService
{
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $token token
     *
     * @return UserModel|null
     */
    public function getUserByToken(string $token)
    {
        $userTokenModel = null;
        try {
            $userTokenModel = UserTokenModel::query()->where('token', $token)->firstOrFail();
            $userTokenModel->touch();

            return $userTokenModel->user;
        } catch (ModelNotFoundException $exception) {
            return null;
        }
    }

    /**
     * @param UserModel    $userModel 用户
     * @param array<mixed> $params    登录参数
     *
     * @return UserTokenModel 登录记录
     */
    public function store(UserModel $userModel, array $params): UserTokenModel
    {
        $userTokenModel = new UserTokenModel();
        $userTokenModel->user_id = $userModel->id;
        $userTokenModel->token = $this->generateToken($userModel);
        $userTokenModel->ua = $params['ua'] ?? '';
        $userTokenModel->ip = $params['ip'] ?? '';
        $userTokenModel->longitude = $params['longitude'] ?? 0;
        $userTokenModel->latitude = $params['latitude'] ?? 0;
        $userTokenModel->save();

        return $userTokenModel;
    }

    /**
     * 软删除用户登录 token.
     *
     * @param UserModel $userModel   用户
     * @param string    $token       token
     * @param bool      $forceDelete 从数据库中移除该记录
     *
     * @return true 幂等响应
     *
     * @throws Exception
     */
    public function destroy(UserModel $userModel, string $token, bool $forceDelete = false): bool
    {
        $logData = [
            'user_id' => $userModel->id,
            'token' => $token,
            'forceDelete' => $forceDelete,
        ];
        $userTokenModel = UserTokenModel::query()->where('token', $token)->first();
        if (!is_null($userTokenModel)) {
            if ($forceDelete) {
                $userTokenModel->forceDelete();
            } else {
                $userTokenModel->delete();
            }
            $logData['user_token'] = $userModel->toArray();
        }
        $this->logger->info('用户退出登录', $logData);

        return true;
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
