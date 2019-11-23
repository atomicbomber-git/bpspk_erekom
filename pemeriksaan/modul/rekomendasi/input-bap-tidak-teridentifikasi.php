<?php

use App\Models\Permohonan;

require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('ul li.nav-ba-tidak-teridentifikasi').addClass('active');
});
</script>
<script src=\"bap.js\"></script>
";

$idpengajuan = U_IDP;
$permohonan = Permohonan::find($idpengajuan);

if (ctype_digit($idpengajuan)) {
    ?>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    Berita Acara Pemeriksaan Tidak Teridentifikasi
                </h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo c_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">
                        Berita Acara Pemeriksaan Tidak Teridentifikasi
                    </li>
                </ol>
            </section>

            <section class="content">
            <?php if($permohonan->hasil_periksa()->count() === 0): ?>
                <?php
                    $found = $sql->get_count('tb_bap_tidak_teridentifikasi', array('ref_idp' => $idpengajuan));
                    if ($found > 0) {
                        include("bap-tidak-teridentifikasi-edit.php");
                    } else {
                        include("bap-tidak-teridentifikasi-add.php");
                    }
                    ?>
            <?php else: ?>
                <div class="alert alert-danger">
                    <i class="fa fa-warning"></i>
                    Berita Acara Pemeriksaan Tidak Teridentifikasi hanya dapat diisi jika hasil pemeriksaan masih kosong.
                </div>
            <?php endif ?>
            </section>
        </div>
    </body>
<?php
}
include(AdminFooter);
?>