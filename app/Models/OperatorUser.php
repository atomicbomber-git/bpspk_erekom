<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OperatorUser extends Model
{
    const LEVEL_ADMIN = 100;

    const STATUS_ACTIVE = 2;
    const STATUS_INACTIVE = 1;

    protected $primaryKey = "idu";
    protected $table = "op_user";

    public function scopeAdmin(Builder $query)
    {
        $query->where("lvl", static::LEVEL_ADMIN);
    }

    public function scopeActive(Builder $query)
    {
        $query->where("status", static::STATUS_ACTIVE);
    }
}