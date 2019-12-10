<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilPeriksa extends Model
{
    protected $primaryKey = "idper";
    protected $table = "tb_hsl_periksa";
    public $timestamps = false;
    public $guarded = [];

    public function jenis_sampel()
    {
        return $this->belongsTo(JenisSampel::class, "ref_jns");
    }

    public function data_ikan()
    {
        return $this->belongsTo(DataIkan::class, "ref_idikan");
    }

    public function satuan_barang()
    {
        return $this->belongsTo(SatuanBarang::class, "id_satuan_barang");
    }
}