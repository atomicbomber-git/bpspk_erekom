<?php

namespace App\Models;

use App\Enums\PermohonanStatusCode;
use Illuminate\Database\Eloquent\Model;

class Permohonan extends Model
{
    protected $primaryKey = "idp";
    protected $table = "tb_permohonan";

    public function scopePersetujuan($query)
    {
        return $query->where(
            "status",
            PermohonanStatusCode::PERSETUJUAN
        );
    }

}