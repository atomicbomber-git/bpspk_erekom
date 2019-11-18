<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NomorSurat extends Model
{
    protected $table = "tb_nosurat";
    public $timestamps = false;
    public $guarded = [];


    public static function getNextNumber()
    {
        return (self::query()
            ->selectRaw("MAX(no_urut) AS max_no_urut")
            ->value("max_no_urut") + 1) ?? 1;
    }
}