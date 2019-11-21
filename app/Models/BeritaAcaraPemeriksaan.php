<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeritaAcaraPemeriksaan extends Model
{
    protected $table = "tb_bap";
    protected $primaryKey = "id_bap";
    public $guarded = [

    ];

    public function petugas1()
    {
        return $this->belongsTo(Pegawai::class, "ptgs1");
    }

    public function petugas2()
    {
        return $this->belongsTo(Pegawai::class, "ptgs2");
    }

    public $timestamps = false;
}