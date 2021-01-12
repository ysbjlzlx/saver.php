<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DataDictModel.
 *
 * @property int    $id
 * @property string $name
 * @property string $key
 * @property string $value
 * @property string $memo
 * @property int    $version
 */
class DataDictModel extends Model
{
    protected $table = 'data_dict';
}
