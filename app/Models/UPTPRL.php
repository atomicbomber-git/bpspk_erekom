<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UPTPRL extends Model
{
    public $timestamps = false;
    protected $primaryKey = "id_upt";
    protected $table = "ref_upt_prl";

    public $fillable = [
        "nama",
        "email",
        "isDelete"
    ];
}