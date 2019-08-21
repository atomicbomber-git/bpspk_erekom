<?php
include ("../../engine/render.php");
$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, datepicker3.css, 
				pnotify.custom.css, jquery.appear.js, select2.css, datatables.css,
				theme.css, default.css, modernizr.js";

$ITEM_FOOT = "jquery.js, jquery.browser.mobile.js, bootstrap.js, nanoscroller.js, bootstrap-datepicker.js, magnific-popup.js, jquery.placeholder.js, 
				pnotify.custom.js, jquery.dataTables.js,datatables.js,select2.js,
				theme.js, theme.init.js,jquery.validate.js,jquery.validate.msg.id.js";

require_once(c_THEMES."auth.php");

$SCRIPT_FOOT = "
	<script>
		$(document).ready(function(){
			$('nav li.nav-dtref').addClass('nav-expanded nav-active');
			$('nav li.df-bk').addClass('nav-active');
		});
	</script>
	<script src=\"custom.js\"></script>
";


$idbk = base64_decode($_GET['landing']) ;
if(empty($idbk) OR !ctype_digit($idbk)){
	_redirect('./balai-karantina.php');
}
$sql->get_row('ref_balai_karantina',array('idbk'=>$idbk),array('idbk','nama','email'));
$row=$sql->result;
$found=$sql->num_rows;

?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Edit Data Balai Karatina</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Edit Data Balai Karatina</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-8">
			<?php
			if($found==0){
				echo '<div class="alert alert-warning">Data Not Found</div>';
			}else{
			?>
			<form id="dtbk_update" class="form-horizontal" method="post">
				<input type="hidden" name="a" id="a" value="updtbk" />
				<input type="hidden" name="idbk" id="idbk" value="<?php echo base64_encode($idbk);?>" />
				<section class="panel">
					<header class="panel-heading">
						<h2 class="panel-title" id="cat-panel-title">Edit Data Balai Karatina</h2>
					</header>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="nama">Nama</label>
								<input name="nama" id="nama" placeholder="Nama" type="text" class="form-control" value="<?php echo $row['nama'];?>">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="email">Email</label>
								<input name="email" id="email" placeholder="Nama Latin" type="text" class="form-control" value="<?php echo $row['email'];?>">
							</div>
						</div>				
					</div>
					<footer class="panel-footer">
						<a href="./balai-karantina.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali </a>
						<button type="submit" id="btn-aksi" name="btn-aksi" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Simpan Perubahan </button>
					</footer>
				</section>
			</form>
			<?php 
			}
			?>
		</div>
	</div>

</section>
</div>
<?php
@include(AdminFooter);
?>