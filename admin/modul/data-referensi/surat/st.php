<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav-dtref').addClass('nav-expanded nav-active');
	$('nav li.nav-redaksi').addClass('nav-expanded');
	$('nav li.red-st').addClass('nav-active');
});
</script>
<script src=\"custom.js\"></script>
";

$sql->get_row('ref_redaksi_surat',array("id"=>1,"jenis"=>1));
$r=$sql->result;
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Redaksi Dasar Surat Tugas</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Redaksi Dasar Surat Tugas</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12">
			<form name="form-st" id="form-st" class="form-horizontal" method="post" action="">
				<input type="hidden" name="a" value="up_st">
				<section class="panel panel-featured panel-featured-primary">
					<header class="panel-heading">
						<div class="panel-actions">
							<a href="#" class="fa fa-caret-down"></a>
						</div>
						<h2 class="panel-title">Redaksi Dasar Surat Tugas Pemeriksaan</h2>
					</header>
					<div class="panel-body">
						<textarea name="isi" class="form-control editor-st"><?php echo $r['bag1'];?></textarea>
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
