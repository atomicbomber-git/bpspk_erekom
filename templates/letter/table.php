<?php

use App\Models\DataIkan;

?>

<table style="width:100%" class="table table-bordered">
    <thead style="background: rgba(0, 0, 0, 0.1)">
        <tr>
            <td style="text-align:center" width="5%">No</td>
            <td style="text-align:center"> Nama Latin </td>
            <td style="text-align:center" width="12%"> Jenis Produk </td>
            <td style="text-align:center" width="12%"> Jumlah Berat (kg) </td>
            <td style="text-align:center" width="12%"> Jumlah Kemasan </td>
            <td style="text-align:center"> No. Segel </td>
            <td style="text-align:center"> Status </td>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($records as $key => $record) : ?>

            <tr>
                <td style="text-align: center" width="5%"><?php echo $key + 1; ?></td>
                <td style="text-align: center"><?php echo  $record['nama_latin']; ?></td>
                <td style="text-align: center"><?php echo $record['keterangan']; ?></td>
                <td style="text-align: center"><?php echo (($record['berat'] == '0.000') ? "" : $record['berat']); ?></td>
                <td style="text-align: center"><?php echo $record['kemasan'] . " " . $record['satuan']; ?></td>
                <td style="text-align: center"><?php echo $record['no_segel']; ?></td>
                <td style="text-align: center">
                    <?php switch ($record['dilindungi']):
                            case DataIkan::STATUS_DILINDUNGI: ?>
                            Dilindungi
                            <?php break ?>
                        <?php
                            case DataIkan::STATUS_TIDAK_DILINDUNGI: ?>
                            Tidak Dilindungi
                            <?php break ?>
                        <?php
                            case DataIkan::STATUS_APPENDIKS_2_CITES ?>
                            Appendiks II CITES
                            <?php break ?>
                    <?php endswitch ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>

    <tfoot>
        <tr>
            <td style="text-align: center; font-weight: bold" colspan="3">
                Total Berat:
            </td>
            <td style="text-align: center">
                <?=
                    array_reduce($records,
                        function ($curr, $next) {
                            return $curr + $next["berat"];
                        }
                    , 0)
                ?>
            </td>
            <td> </td>
            <td> </td>
            <td> </td>
        </tr>
    </tfoot>
</table>