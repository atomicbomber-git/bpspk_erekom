<?php
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
?>
<body class="hold-transition skin-blue sidebar-mini">

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
</div>

</div>
</body>
<?php
}
include(AdminFooter);
?>