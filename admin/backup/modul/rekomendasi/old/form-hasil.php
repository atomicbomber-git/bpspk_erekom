<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav2').addClass('nav-active');
});
</script>
<script src=\"custom-2.js\"></script>
";

$idpengajuan=base64_decode($_GET['data']);
if(ctype_digit($idpengajuan)){
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Input Hasil Pemeriksaan</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><a href="./pemeriksaan-sample.php"><span>Pemeriksaan Sampel</span></a></li>
				<li><span>Input Hasil Pemeriksaan</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<?php
	
	$sql->get_row('tb_pemeriksaan',array('ref_idp'=>$idpengajuan),array('tgl_periksa','id_periksa'));
	$found=$sql->num_rows;
	if($found>0){
		$r=$sql->result;
		$tgl_periksa=date('m/d/Y',strtotime($r['tgl_periksa']));
		$idperiksa=$r['id_periksa'];
		include ("fh-edit.php");
	}else{
		include ("fh-add.php");
	}
	
	?>
</section>
<?php
}
include(AdminFooter);
?>
