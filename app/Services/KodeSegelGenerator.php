<?php

namespace App\Services;

use App\Services\Contracts\KodeSegelGenerator as ContractsKodeSegelGenerator;

class KodeSegelGenerator implements ContractsKodeSegelGenerator
{
    public function generate(
        $bap_number, /* Nomor BAP */
        $year,
        $upt_code,
        $product_code,
        $package_code /* Nomor kemasan */
    )
    {
        return sprintf(
            "%s-%s-%s-%s-%s",
            $bap_number,
            $year,
            $upt_code,
            $product_code,
            $package_code,
        );
    }
}