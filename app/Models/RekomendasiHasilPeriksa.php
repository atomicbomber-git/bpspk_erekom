<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekomendasiHasilPeriksa extends Model
{
    protected $primaryKey = "idtb";
    protected $table = "tb_rek_hsl_periksa";
    public $timestamps = false;
    public $guarded = [];

    public function rekomendasi()
    {
        return $this->belongsTo(Rekomendasi::class, "ref_idrek");
    }

    public function satuan_barang()
    {
        return $this->belongsTo(SatuanBarang::class);
    }

    public function data_ikan()
    {
        return $this->belongsTo(DataIkan::class, "ref_idikan");
    }
}