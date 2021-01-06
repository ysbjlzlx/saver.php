<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserTokenModel.
 */
class UserTokenModel extends Model
{
    protected $table = 'user_table';

    /**
     * @return BelongsTo token 关联用户
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
