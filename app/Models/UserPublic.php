<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPublic extends Model
{
    protected $table = "tb_userpublic";
    protected $primaryKey = "iduser";

    public function metadata()
    {
        return $this->hasMany(WebMeta::class, "ref_id");
    }

    public function verification()
    {
        return $this
            ->hasOne(WebMeta::class, "ref_id")
            ->where("meta_key", WebMeta::KEY_U_VERIFY);
    }
}