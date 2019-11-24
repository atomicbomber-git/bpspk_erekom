<?php

use App\Models\Permohonan;
use App\Services\Formatter;
use Jenssegers\Date\Date;

require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('ul li.nav-rek').addClass('active');
});
$('.sl2').select2();
</script>
<script src=\"js-rekomendasi.js\"></script>
";

$formatter = container(Formatter::class);

$idpengajuan=U_IDP;
if(ctype_digit($idpengajuan)){
	include "function.php";
	$arr_satuan=array(
		""=>"-Pilih-",
		"Colly"=>"Colly",
		"Container"=>"Container",
		"Truk"=>"Truk",
		"Ekor"=>"Ekor"
		);


		$permohonan = Permohonan::find($idpengajuan);
		$tanggal_dua_hari_kedepan = $formatter->fancyDate(Date::today()->addDay(2));
		$tanggal_dua_minggu_kedepan = $formatter->fancyDate(Date::today()->addWeek(2));

?>
<body class="hold-transition skin-blue sidebar-mini">

<script>
	var tanggal_dua_hari_kedepan =  "<?= $tanggal_dua_hari_kedepan; ?>"
	var tanggal_dua_minggu_kedepan =  "<?= $tanggal_dua_minggu_kedepan; ?>"
</script>

<div class="content-wrapper">

	<section class="content-header">
		<h1>
		Draft Surat Rekomendasi
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo c_URL;?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Draft Surat Rekomendasi</li>
		</ol>
	</section>

	<section class="content">
		<?php if($permohonan->hasil_periksa()->count() > 0): ?>
			<?php
				$sql->get_all('ref_data_ikan');
				$arr_ikan=array();
				foreach ($sql->result as $ik) {
					$arr_ikan[$ik['id_ikan']]=array(
						"nama"=>$ik['nama_ikan'],
						"latin"=>$ik['nama_latin']);
				}
				$sql->get_all('ref_jns_sampel');
				$arr_produk=array();
				foreach ($sql->result as $pr) {
					$arr_produk[$pr['id_ref']]=array(
						"nama"=>$pr['jenis_sampel']);
				}

				$found=$sql->get_count('tb_rekomendasi',array('ref_idp'=>$idpengajuan));
				if($found>0){
					include ("rek-edit.php");
				}else{
					include ("rek-add.php");
				}
			?>
		<?php else: ?>
			<div class="alert alert-danger">
				<i class="fa fa-warning"></i>
				Draft Surat Rekomendasi hanya dapat diisi jika hasil pemeriksaan telah diisi.
			</div>
		<?php endif ?>
	</section>
</div>

</div>
</body>
<?php
}
include(AdminFooter);
?>