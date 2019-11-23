<?php

use App\Models\RekomendasiHasilPeriksa;
use App\Services\Formatter;
use Jenssegers\Date\Date;

include ("../../engine/render.php");
    define ("VENDOR", c_STATIC."assets/vendor/");

    $rekomendasi_query_builder = 
        RekomendasiHasilPeriksa::query()
            ->with([
                "data_ikan",
                "rekomendasi",
                "rekomendasi.permohonan",
                "rekomendasi.permohonan.berita_acara_pemeriksaan",
                "rekomendasi.permohonan.berita_acara_pemeriksaan.petugas1",
                "rekomendasi.permohonan.berita_acara_pemeriksaan.petugas2",
                "rekomendasi.user_public:iduser,nama_lengkap",
                "satuan_barang",
            ])
            ->whereHas("rekomendasi", function ($query) {
                $query
                    ->when($_POST["filter_waktu"], function ($query, $filter_waktu) {
                        switch ($filter_waktu) {
                            case 'tahun':
                                $query->whereRaw("YEAR(tgl_surat) BETWEEN ? AND ?", [
                                    $_POST["filter_tahun"],
                                    $_POST["filter_tahun2"],
                                ]);
                                break;
                            case 'bulan':
                                list($start_tahun, $start_bulan) = explode("-", $_POST["filter_bulan"]);
                                list($end_tahun, $end_bulan) = explode("-", $_POST["filter_bulan2"]);

                                $date_start = Date::create($start_tahun, $start_bulan)->firstOfMonth();
                                $date_end = Date::create($end_tahun, $end_bulan)->lastOfMonth();

                                $query->whereRaw("DATE(tgl_surat) BETWEEN ? AND ?", [$date_start, $date_end]);
                                break;
                        }
                    });
            });

    $rekomendasi_hasil_periksa =
        $rekomendasi_query_builder->get();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Laporan Statistik </title>

    <style>
        body{
            padding:10px;
        }
        table
        {	
            width:100%;
            font-family : arial;
            font-size: 12px;
            border-collapse: collapse;
            border: thin solid black;
        }
        th, td
        {
            padding:5px;
            border: thin solid black;
        }
    </style>
</head>

<body>
    <table id="dtlap">
        <thead>
            <tr>
                <th> No. </th>
                <th> Nama </th>
                <th> No. Rekomendasi </th>
                <th> Tgl. Rekomendasi </th>
                <th> Tujuan Pengiriman </th>
                <th> Jenis </th>
                <th> Ikan </th>
                <th> Berat (Kg) </th>
                <th> Petugas 1 </th>
                <th> Petugas 2 </th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($rekomendasi_hasil_periksa as $key => $hasil_periksa): ?>
                <tr>
                    <td> <?= $key + 1 ?> </td>
                    <td> <?= $hasil_periksa->rekomendasi->user_public->nama_lengkap ?> </td>
                    <td> <?= $hasil_periksa->rekomendasi->no_surat ?> </td>
                    <td> <?= Formatter::fancyDate($hasil_periksa->rekomendasi->tgl_surat) ?> </td>
                    <td> <?= $hasil_periksa->rekomendasi->tujuan ?> </td>
                    <td>
                        <?= $hasil_periksa->produk ?>
                        <?= $hasil_periksa->kondisi_produk ?>
                        <?= $hasil_periksa->jenis_produk ?>
                    
                    </td>
                    <td> <?= $hasil_periksa->data_ikan->nama_ikan ?> </td>
                    <td> <?= $hasil_periksa->berat ?> </td>
                    <td> <?= $hasil_periksa->rekomendasi->permohonan->berita_acara_pemeriksaan->petugas1->nm_lengkap ?> </td>
                    <td> <?= $hasil_periksa->rekomendasi->permohonan->berita_acara_pemeriksaan->petugas2->nm_lengkap ?> </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>
</html>