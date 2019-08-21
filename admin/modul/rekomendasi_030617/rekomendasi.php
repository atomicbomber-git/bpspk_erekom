<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav2').addClass('nav-active');
	// $('#btn_add_data').click(function(){
	// 	var tr = $('tr.dt:first');
	// 	tr.find('.sl2').select2('destroy');
	// 	var clone = tr.clone();
	// 	clone.find(':text').val('');
	// 	clone.find('select').val('');

	// 	$('tr.dt:last').after(clone);
 	//	tr.find('.sl2').select2();
 	//  clone.find('.sl2').select2();
	// });

	// $('.del_thisrow2').click(function(e) {
	// 	e.preventDefault();
	// 	$(this).closest('tr').remove();
	// });
});
$('.sl2').select2();
</script>
<script src=\"js-rekomendasi.js\"></script>
";

$idpengajuan=base64_decode($_GET['data']);
if(ctype_digit($idpengajuan)){
	include "function.php";
	$arr_satuan=array(
		""=>"-Pilih-",
		"Colly"=>"Colly",
		"Container"=>"Container",
		"Truk"=>"Truk",
		"Ekor"=>"Ekor"
		);
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Draft Surat Rekomendasi</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><a href="./pemeriksaan-sample.php"><span>Pemeriksaan Sampel</span></a></li>
				<li><span>Draft Surat Rekomendasi</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
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
</section>

<div id="DelModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<section class="panel">
		<header class="panel-heading">
			<h2 class="panel-title">Reload Ulang Data?</h2>
		</header>
		<div class="panel-body">
			<div class="modal-wrapper">
				<div class="modal-icon">
					<i class="fa fa-question-circle"></i>
				</div>
				<div class="modal-text">
					<p>Apakah anda yakin akan melakuakn reload ulang data dari hasil pemeriksaan lapangan?</p>
				</div>
			</div>
		</div>
		<footer class="panel-footer">
			<div class="row">
				<div class="col-md-12 text-right">
					<button class="btn btn-primary modal-confirm">Ya, Reload Ulang</button>
					<button class="btn btn-default modal-dismiss">Tidak</button>
				</div>
			</div>
		</footer>
	</section>
</div>

<?php
}
include(AdminFooter);
?>
