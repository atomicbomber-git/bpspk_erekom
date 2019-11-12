<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekomendasiHasilPeriksa extends Model
{
    protected $primaryKey = "idtb";
    protected $table = "tb_rek_hsl_periksa";
    public $timestamps = false;
    public $guarded = [];
}