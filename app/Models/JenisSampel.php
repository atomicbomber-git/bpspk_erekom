<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSampel extends Model
{
    protected $primaryKey = "idrek";
    protected $table = "ref_jns_sampel";
    public $timestamps = false;
    public $guarded = [];
}