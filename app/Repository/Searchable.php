<?php

namespace App\Repository;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Searchable.
 *
 * @see https://github.com/eddy8/lightCMS/blob/master/app/Repository/Searchable.php
 */
trait Searchable
{
    public static function buildQuery(Builder $query, array $condition): Builder
    {
        // 获取模型定义的搜索域
        $model = $query->getModel();
        $searchField = [];
        if (property_exists($model, 'searchField')) {
            $searchField = $model::$searchField;
        }

        foreach ($condition as $k => $v) {
            if (!is_array($v) && isset($searchField[$k]['searchType'])) {
                $condition[$k] = [$searchField[$k]['searchType'], $v];
            }
        }

        foreach ($condition as $k => $v) {
            $type = 'like';
            $value = $v;
            if (is_array($v)) {
                list($type, $value) = $v;
            }
            $value = trim($value);
            // 搜索值为空字符串则忽略该条件
            if ('' === $value) {
                continue;
            }

            if ('created_at' === $k ||
                'updated_at' === $k ||
                (isset($searchField[$k]['showType']) && 'datetime' === $searchField[$k]['showType'])
            ) {
                $dates = explode(' ~ ', $value);
                if (2 === count($dates)) {
                    $query->whereBetween($k, [
                        Carbon::parse($dates[0])->startOfDay(),
                        Carbon::parse($dates[1])->endOfDay(),
                    ]);
                }
            } elseif (isset($searchField[$k]['searchType']) && 'whereRaw' === $searchField[$k]['searchType']) {
                $queryParams = array_pad([], substr_count($searchField[$k]['searchCondition'], '?'), $value);
                $query->whereRaw($searchField[$k]['searchCondition'], $queryParams);
            } else {
                $query->where($k, $type, 'like' === $type ? "%{$value}%" : $value);
            }
        }

        return $query;
    }
}
