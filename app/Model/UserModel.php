<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserModel.
 *
 * @property string $username 用户名
 * @property string $password 密码
 */
class UserModel extends Model
{
    protected $table = 'user';
    /**
     * 隐藏字段.
     *
     * @var string[]
     */
    protected $hidden = ['password'];
}
