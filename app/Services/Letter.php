<?php

namespace App\Services;

class Letter
{
    public function getHeaderContentHTML($imageSourcepath)
    {
        return "
            <table style=\"width:100%\">
                <tr>
                    <td> <img style=\"vertical-align: top\" src=\"{$imageSourcepath}logo-kkp-kop.png\" width=\"100\"> </td>
                    <td style=\"text-align: center;\"><h4><strong>KEMENTERIAN KELAUTAN DAN PERIKANAN</strong></h4>
                    <h5>DIREKTORAT JENDERAL PENGELOLAAN RUANG LAUT<h5>
                    <h4><strong> LOKA PENGELOLAAN SUMBER DAYA PESISIR DAN LAUT SERANG</strong></h4>
                        <small> JALAN RAYA CARITA KM 4.5, DESA CARINGIN KEC. LABUAN KAB. PANDEGLANG PROV. BANTEN </small> <br/>
                        TELEPON (0253) 802626, FAKSIMILI (0253) 802616
                    </td>
                </tr>
                <tr><td colspan=\"2\"><hr style=\"margin:0;border:#000\"></td></tr>
            </table>
        ";
    }
}