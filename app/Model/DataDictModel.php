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

    /**
     * @param mixed $value 序列化 value
     */
    public function setValueAttribute($value): void
    {
        $this->attributes['value'] = serialize($value);
    }

    /**
     * @param string $value 反序列化 value
     *
     * @return mixed
     */
    public function getValueAttribute(string $value)
    {
        return unserialize($value);
    }
}
