<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeritaAcaraPemeriksaanTidakTeridentifikasi extends Model
{
    protected $table = "tb_bap_tidak_teridentifikasi";
    protected $primaryKey = "id_bap";
    public $guarded = [

    ];

    public $timestamps = false;
}