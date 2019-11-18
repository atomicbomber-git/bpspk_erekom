<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilPeriksa extends Model
{
    protected $primaryKey = "idper";
    protected $table = "tb_hsl_periksa";
    public $timestamps = false;
    public $guarded = [];
}