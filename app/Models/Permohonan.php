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

    public function user()
    {
        return $this->belongsTo(UserPublic::class, "ref_iduser");
    }

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

    public function pemeriksaan()
    {
        return $this->hasOne(Pemeriksaan::class, "ref_idp");
    }

    public function rekomendasi()
    {
        return $this->hasOne(Rekomendasi::class, "ref_idp");
    }

    public function hasil_periksa()
    {
        return $this->hasMany(HasilPeriksa::class, "ref_idp");
    }

    public function berita_acara_pemeriksaan_tidak_teridentifikasi()
    {
        return $this->hasOne(BeritaAcaraPemeriksaanTidakTeridentifikasi::class, "ref_idp");
    }

    public function berita_acara_pemeriksaan()
    {
        return $this->hasOne(BeritaAcaraPemeriksaan::class, "ref_idp");
    }

    public function scopePersetujuan($query)
    {
        return $query->where(
            "status",
            PermohonanStatusCode::PERSETUJUAN
        );
    }

}