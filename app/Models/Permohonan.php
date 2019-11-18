<?php

namespace App\Models;

use App\Enums\PermohonanStatusCode;
use Illuminate\Database\Eloquent\Model;

class Permohonan extends Model
{
    protected $primaryKey = "idp";
    protected $table = "tb_permohonan";
    public $timestamps = false;
    public $guarded = [];

    const STATUS_VERIFIKASI = 1;
    const STATUS_DITERIMA = 2;

    public function petugas()
    {
        return $this->hasMany(PetugasLapangan::class, "ref_idp");
    }

    public function nomor_surat()
    {
        return $this->hasOne(NomorSurat::class, "ref_idp");
    }

    public function satuan_kerja()
    {
        return $this->belongsTo(SatuanKerja::class, "ref_satker");
    }

    public function rekomendasi()
    {
        return $this->hasOne(Rekomendasi::class, "ref_idp");
    }

    public function hasil_periksa()
    {
        return $this->hasMany(HasilPeriksa::class, "ref_idp");
    }

    public function scopePersetujuan($query)
    {
        return $query->where(
            "status",
            PermohonanStatusCode::PERSETUJUAN
        );
    }

}