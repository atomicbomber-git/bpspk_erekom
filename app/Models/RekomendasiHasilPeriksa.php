<?php

namespace App\Models;

use App\Constants\ProductClassification;
use Illuminate\Database\Eloquent\Model;

class RekomendasiHasilPeriksa extends Model
{
    protected $primaryKey = "idtb";
    protected $table = "tb_rek_hsl_periksa";
    public $timestamps = false;
    public $guarded = [];

    public function rekomendasi()
    {
        return $this->belongsTo(Rekomendasi::class, "ref_idrek");
    }

    public function satuan_barang()
    {
        return $this->belongsTo(SatuanBarang::class);
    }

    public function data_ikan()
    {
        return $this->belongsTo(DataIkan::class, "ref_idikan");
    }

    public function jenis_sampel()
    {
        return $this->belongsTo(JenisSampel::class, "ref_jns", "id_ref");
    }

    public function productCode()
    {
        $product_classifications = ProductClassification::get();
        $product_code = $product_classifications[$this->produk]["code"] ?? "";
        $product_condition_classifications = $product_classifications[$this->produk]["items"] ?? [];
        $product_condition_code =  $product_condition_classifications[$this->kondisi_produk]["code"];
        $product_type_classifications =  $product_condition_classifications[$this->kondisi_produk]["items"] ?? [];
        $product_type_code = $product_type_classifications[$this->jenis_produk] ?? "";

        return sprintf(
            "%s%s%s",
            $product_code,
            $product_condition_code,
            $product_type_code,
        );
    }

    public function packageCode()
    {
        return $this->no_segel . "/" . $this->no_segel_akhir;
    }
}