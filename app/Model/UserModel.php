<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserModel.
 *
 * @property int    $id          编号
 * @property string $username    用户名
 * @property string $password    密码
 * @property string $totp_secret secret
 */
class UserModel extends Model
{
    protected $table = 'user';
    /**
     * 隐藏字段.
     *
     * @var string[]
     */
    protected $hidden = ['password', 'totp_secret'];

    public function tokens()
    {
        return $this->hasMany(UserTokenModel::class, 'user_id');
    }
}
