<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    public $timestamps = false;
    protected $table = "tb_pemeriksaan";
    protected $primaryKey = "id_periksa";
}