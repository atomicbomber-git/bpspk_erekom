<?php

namespace App\Services\Contracts;

interface KodeSegelGenerator
{
    public function generate(
        $bap_number, /* Nomor BAP */
        $year,
        $upt_code,
        $product_code,
        $package_code /* Nomor kemasan */
    );
}