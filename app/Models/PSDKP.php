<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PSDKP extends Model
{
    public $timestamps = false;
    protected $primaryKey = "id_psd";
    protected $table = "ref_psdkp";

    public $fillable = [
        "nama",
        "email",
        "isDelete"
    ];
}