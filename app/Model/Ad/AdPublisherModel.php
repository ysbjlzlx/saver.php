<?php

namespace App\Model\Ad;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdPublisherModel.
 * 流量主模型.
 *
 * @property int           $id
 * @property string        $username
 * @property string        $password
 * @property string        $email
 * @property string|Carbon $email_verified_at
 * @property string|Carbon $updated_at
 * @property string|Carbon $created_at
 */
class AdPublisherModel extends Model
{
    protected $table = 'ad_publisher';
    protected $hidden = ['password'];
    protected $dates = [
        'email_verified_at', 'updated_at', 'created_at',
    ];
    public static $searchField = [
        'username' => [
            'searchType' => '=',
        ],
    ];
}
