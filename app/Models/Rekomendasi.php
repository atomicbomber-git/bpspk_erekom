<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    protected $primaryKey = "idrek";
    protected $table = "tb_rekomendasi";
    public $timestamps = false;
    public $guarded = [];

    public function hasil_periksa()
    {
        return $this->hasMany(RekomendasiHasilPeriksa::class, "ref_idrek");
    }

    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class, "ref_idp");
    }

    public function user_public()
    {
        return $this->belongsTo(UserPublic::class, "ref_iduser");
    }

    public function letterNumber()
    {
        return explode("/", $this->no_surat)[0] ?? "-";
    }
}