<?php

namespace App\Casters;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EloquentCaster 
{
    public static function castCollection(Collection $collection)
    {
        return $collection->toArray();
    }

    public static function castModel(Model $model)
    {
        return $model->toArray();
    }
}