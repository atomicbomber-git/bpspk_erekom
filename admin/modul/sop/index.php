<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav-sop').addClass('nav-active');
});
</script>
<script src=\"custom.js\"></script>
";

$sql->get_row('tb_maklumat',array("id"=>1));
$r=$sql->result;
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Prosedur Pelayanan LPSPL Serang</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Prosedur Pelayanan LPSPL Serang</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12">
			<form name="form_maklumat" id="form_maklumat" class="form-horizontal" method="post" action="">
				<input type="hidden" name="a" value="update_m">
				<section class="panel panel-featured panel-featured-primary">
					<header class="panel-heading">
						<div class="panel-actions">
							<a href="#" class="fa fa-caret-down"></a>
						</div>
						<h2 class="panel-title">Prosedur Pelayanan LPSPL Serang</h2>
					</header>
					<div class="panel-body">
						<textarea name="isi" class="form-control editor"><?php echo $r['isi_maklumat'];?></textarea>
					</div>
					<footer class="panel-footer">
						<button class="btn btn-sm btn-primary" id="btn-aksi">Simpan</button>
					</footer>
				</section>
			</form>
		</div>
	</div>
</section>
<?php
include(AdminFooter);
?>
