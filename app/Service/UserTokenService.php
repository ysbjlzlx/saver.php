<?php

namespace App\Service;

use App\Model\UserTokenModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserTokenService
{
    /**
     * @param  string  $token
     * @return null
     */
    public function getUserByToken(string $token)
    {
        $userTokenModel = null;
        try {
            $userTokenModel = UserTokenModel::query()->where('token', $token)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            return null;
        }

    }
}
