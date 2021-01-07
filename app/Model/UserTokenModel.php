<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UserTokenModel.
 *
 * @property int       $id
 * @property int       $user_id
 * @property string    $token
 * @property string    $ua
 * @property string    $ip
 * @property string    $longitude
 * @property string    $latitude
 * @property UserModel $user
 */
class UserTokenModel extends Model
{
    use SoftDeletes;
    protected $table = 'user_token';

    /**
     * @return BelongsTo token 关联用户
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
