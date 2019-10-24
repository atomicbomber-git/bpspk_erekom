<?php

namespace App\Casters;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class EloquentCaster 
{
    public function castCollection(Collection $collection)
    {
        return $collection->toArray();
    }

    public function castModel(Model $model)
    {
        return $model->toArray();
    }
}