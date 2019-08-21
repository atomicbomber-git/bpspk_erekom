<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('ul li.nav-ba').addClass('active');
});
</script>
<script src=\"bap.js\"></script>
";

$idpengajuan=U_IDP;
if(ctype_digit($idpengajuan)){
?>
<body class="hold-transition skin-blue sidebar-mini">

<div class="content-wrapper">
	<section class="content-header">
		<h1>
		Berita Acara Pemeriksaan
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo c_URL;?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Berita Acara Pemeriksaan</li>
		</ol>
	</section>
	<section class="content">
	<?php
	
	$found=$sql->get_count('tb_bap',array('ref_idp'=>$idpengajuan));
	if($found>0){
		include ("bap-edit.php");
	}else{
		include ("bap-add.php");
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
