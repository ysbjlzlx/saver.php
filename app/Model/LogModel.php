<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LogModel.
 *
 * @property int           $id
 * @property string        $message
 * @property array         $context
 * @property int           $level
 * @property string        $level_name
 * @property string        $channel
 * @property string|Carbon $datetime
 * @property array         $extra
 */
class LogModel extends Model
{
    protected $table = 'log';
    protected $casts = [
        'context' => 'array',
        'extra' => 'array',
    ];

    public function getDatetimeAttribute(): Carbon
    {
        return Carbon::createFromFormat(Carbon::ATOM, $this->datetime);
    }
}
