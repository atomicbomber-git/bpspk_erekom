<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    protected $primaryKey = "idrek";
    protected $table = "tb_rekomendasi";
    public $timestamps = false;
    public $guarded = [];
}