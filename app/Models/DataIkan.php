<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataIkan extends Model
{
    const STATUS_DILINDUNGI = 1;
    const STATUS_TIDAK_DILINDUNGI = 2;
    const STATUS_APPENDIKS_2_CITES = 1;

    const STATUSES = [
        self::STATUS_DILINDUNGI => "Dilindungi",
        self::STATUS_TIDAK_DILINDUNGI => "Tidak Dilindungi",
        self::STATUS_APPENDIKS_2_CITES => "Appendiks II Cites",
    ];

    protected $table = "ref_data_ikan";
    protected $primaryKey = "id_ikan";
}