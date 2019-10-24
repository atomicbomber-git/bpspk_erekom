<?php

include_once("../bootstrap.php");
include_once("engine/render.php");


$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, datepicker3.css, bootstrap-multiselect.css,pnotify.custom.css, datatables.css, fileinput.min.css, theme.css, default.css, theme-custom.css,  modernizr.js";
$ITEM_FOOT = "jquery.js, jquery.browser.mobile.js, bootstrap.js, nanoscroller.js, bootstrap-datepicker.js, magnific-popup.js, jquery.placeholder.js, bootstrap-multiselect.js, jquery.dataTables.js,ckeditor.js, datatables.js, fileinput.min.js, jquery.flot.js, jquery.flot.tooltip.js, jquery.flot.categories.js,pnotify.custom.js,jquery.validate.js,jquery.validate.msg.id.js snap.svg.js, liquid.meter.js, theme.js, theme.init.js";

require_once(c_THEMES . "auth.php");

$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.landing').addClass('nav-active');
});
</script>
";

$profilpic = "!logged-user.jpg";

$arr_status = array(
    1 => "Pemeriksaan Data.",
    2 => "Data Diterima, Pengajuan Sedang Diproses Oleh Admin.",
    3 => "Data Ditolak, Berkas/Data Tidak Lengkap.",
    4 => "Pemeriksaan Sampel Telah Dilakukan.",
    5 => "Surat Rekomendasi Sudah Diterbitkan."
);
?>
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Dashboard</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="<?php echo c_MODULE; ?>">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Dashboard</span></li>
            </ol>

            <a class="sidebar-right-toggle"><i class="fa fa-chevron-left"></i></a>
        </div>
    </header>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-primary">
                <strong>Hi <?php echo U_NAME; ?>,Selamat Datang.</strong> <span class="pull-right">Hari ini : <?php echo tanggalIndo(date('Y-m-d H:i:s'), "l, j F Y H:i"); ?> </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">
            <section class="panel">
                <header class="panel-heading">
                    <div class="panel-actions">
                        <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                    </div>

                    <h2 class="panel-title">Pemberitahuan</h2>
                </header>
                <div class="panel-body">
                    <?php
                    $pesan = "Tidak Ada Pemberitahuan.";
                    if (!container(App\Services\Auth::class)->isVerified()) {  ?>

                        <div class="alert alert-warning fade in nomargin">
                            <h4>Hi <?php echo U_NAME; ?></h4>
                            <p>Untuk dapat <strong>mengajukan permohonan rekomendasi</strong>, Anda harus melakukan verifikasi akun dengan memasukkan kode khusus yang telah dikirim ke email anda.</p>
                            <p>
                                <a href="?verifikasi" class="btn btn-info mt-xs mb-xs">Verifikasi Sekarang.</a>
                            </p>
                        </div>

                    <?php
                        $pesan = "";
                    }

                    $check_bio = $sql->get_count('tb_biodata', array('ref_iduser' => U_ID));
                    if ($check_bio == 0) {
                        ?>
                        <div class="alert alert-warning fade in nomargin">
                            <h4>Hi <?php echo U_NAME; ?></h4>
                            <p>Segera lengkapi biodata anda sebagai syarat untuk <strong>mengajukan permohonan rekomendasi</strong>.</p>
                            <p>
                                <a href="?biodata" class="btn btn-info mt-xs mb-xs">Isi Biodata Sekarang.</a>
                            </p>
                        </div>
                    <?php
                        $pesan = "";
                    }

                    echo $pesan;
                    ?>
                </div>
            </section>
        </div>
        <div class="col-md-5">
            <section class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6 text-center"><a href="?pengajuan" style="text-decoration:none"><img src="<?php echo IMAGES . "document.png"; ?>"><br>Pengajuan<br>Rekomendasi </a></div>
                        <div class="col-md-6 text-center"><a href="?biodata" style="text-decoration:none"><img src="<?php echo IMAGES . "user.png"; ?>"><br>Biodata</a></div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <section class="panel">
                <header class="panel-heading">
                    <div class="panel-actions">
                        <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                    </div>

                    <h2 class="panel-title">Status Pengajuan Rekomendasi</h2>
                </header>
                <div class="panel-body">
                    <table class="table table-hover">
                        <tr>
                            <th>No</th>
                            <th>Tujuan</th>
                            <th>Tanggal Pengajuan</th>
                            <th>No Antrian</th>
                            <th>Status</th>
                        </tr>
                        <?php
                        $p = $sql->run("SELECT u.nama_lengkap, p.idp, p.tgl_pengajuan, p.penerima, p.tujuan, p.status,p.tgl_pelayanan,p.no_antrian, (SELECT pesan FROM tb_hsl_verifikasi WHERE ref_idp=p.idp ORDER BY date_act DESC LIMIT 1) as isi_pesan, tr.kode_surat FROM tb_permohonan p
							LEFT JOIN tb_rekomendasi tr ON(tr.ref_idp=p.idp) 
							JOIN tb_userpublic u ON(u.iduser=p.ref_iduser) WHERE p.ref_iduser='" . U_ID . "' ORDER BY p.tgl_pengajuan DESC LIMIT 15");
                        if ($p->rowCount() > 0) {
                            $no = 0;
                            foreach ($p->fetchAll() as $rowp) {
                                switch ($rowp['status']) {
                                    case 3:
                                        $class = "";
                                        $pesan = "<br/><span class='text-alert alert-danger'>" . $rowp['isi_pesan'] . "</span>
										<br/><a href='" . c_URL . $ModuleDir . "pengajuan/edit.php?token=" . md5($rowp['idp'] . U_ID . "editp") . "&data=" . base64_encode($rowp['idp']) . "'>Edit Data</a>";
                                        break;

                                    case 5:
                                        $class = "info";
                                        $pesan = "<br/><a target='_blank' href='" . c_DOMAIN_UTAMA . "download.php?surat=" . $rowp['kode_surat'] . "&token=" . md5('download' . $rowp['kode_surat'] . 'public') . "'>Download</a>";
                                        break;

                                    default:
                                        $pesan = "";
                                        $class = "";
                                        break;
                                }
                                $no++;
                                echo '<tr class="' . $class . '">
								<td>' . $no . '</td>
								<td>' . $rowp['penerima'] . '<br/>' . $rowp['tujuan'] . '</td>
								<td>' . tanggalIndo($rowp['tgl_pengajuan'], "j F Y") . '</td>
								<td>' . format_noantrian($rowp['tgl_pelayanan'], $rowp['no_antrian']) . '</td>
								<td>' . $arr_status[$rowp['status']] . $pesan . '</td>
								</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5">Tidak Ada Riwayat Pengajuan Rekomendasi</td></tr>';
                        }
                        ?>
                    </table>
                </div>
            </section>
        </div>
    </div>
</section>
<?php
@include(AdminFooter);
?>