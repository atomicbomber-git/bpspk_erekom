<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav-kuis').addClass('nav-expanded nav-active');
	$('nav li.kuis-p').addClass('nav-active');
});
</script>
<script src=\"custom.js?t=".time()."\"></script>
";

$idq = base64_decode($_GET['p']) ;
if(empty($idq) OR !ctype_digit($idq)){
	_redirect('./list-pertanyaan.php');
}
$sql->get_row('tb_kuisioner_q',array('id_q'=>$idq),array('pertanyaan'));
$question=$sql->result;
$found=$sql->num_rows;

?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Edit Pertanyaan</h2>
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
				<li><span>Edit Pertanyaan</span></li>
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
			<form id="dtikan_update" class="form-horizontal" method="post">
				<input type="hidden" name="a" id="a" value="update_q" />
				<input type="hidden" name="idq" id="idq" value="<?php echo base64_encode($idq);?>" />
				<section class="panel">
					<header class="panel-heading">
						<h2 class="panel-title" id="cat-panel-title">Edit Pertanyaan</h2>
					</header>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-md-12">							
								<label class="control-label" for="pertanyaan">Pertanyaan</label>
								<input name="pertanyaan" id="pertanyaan" placeholder="Pertanyaan" type="text" class="form-control" value="<?php echo $question['pertanyaan'];?>">
							</div>
						</div>			
					</div>
					<footer class="panel-footer">
						<a href="./list-pertanyaan.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali </a>
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