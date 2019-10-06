<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataIkan extends Model
{
    const STATUS_DILINDUNGI = 1;
    const STATUS_TIDAK_DILINDUNGI = 2;

    protected $table = "ref_data_ikan";
    protected $primaryKey = "id_ikan";
}