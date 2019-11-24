<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetugasLapangan extends Model
{
    protected $primaryKey = "id_pl";
    protected $table = "tb_petugas_lap";

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, "ref_idpeg");
    }
}