<?php

namespace App\Services;

class Letter
{
    private $app_name;

    public function __construct() {
        $this->app_name = container("app_name");
    }

    public function getHeaderContentHTML($imageSourcepath = null)
    {
        $app_name = strtoupper($this->app_name);

        return "
            <table style=\"width:100%\">
                <tr>
                    <td> <img style=\"vertical-align: top\" src=\"{$imageSourcepath}logo-kkp-kop.png\" width=\"100\"> </td>
                    <td style=\"text-align: center;\"><h4><strong>KEMENTERIAN KELAUTAN DAN PERIKANAN</strong></h4>
                    <h5>DIREKTORAT JENDERAL PENGELOLAAN RUANG LAUT<h5>
                    <h4><strong> $app_name </strong></h4>
                        <small> JALAN RAYA CARITA KM 4.5, DESA CARINGIN KEC. LABUAN KAB. PANDEGLANG PROV. BANTEN </small> <br/>
                        TELEPON (0253) 802626, FAKSIMILI (0253) 802616
                    </td>
                </tr>
                <tr><td colspan=\"2\"><hr style=\"margin:0;border:#000\"></td></tr>
            </table>
        ";
    }

    public function getOpeningText($tanggal_pengajuan, $tujuan, $jenis_angkutan, $no_bap, $tanggal_bap)
    {
        return "Menindaklanjuti Surat Saudara tanggal $tanggal_pengajuan perihal permohonan rekomendasi untuk lalu lintas hiu/pari ke $tujuan melalui jalur $jenis_angkutan, dengan ini disampaikan bahwa Petugas $this->app_name telah melakukan identifikasi yang tertuang dalam Berita Acara Nomor: $no_bap tanggal $tanggal_bap dengan hasil:";
    }
}