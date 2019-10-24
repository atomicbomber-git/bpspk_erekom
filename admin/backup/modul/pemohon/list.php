<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.navph').addClass('nav-active');
});
</script>
<script src=\"custom.js?t=".time()."\"></script>
";
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Data Pemohon</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Rekomendasi</span></li>
				<li><span>Data Pemohon</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-9">
		</div>
		<div class="col-md-3">
			<form action="" method="POST" id="form_cari" name="form_cari" class="search">
				<div class="input-group input-search">
					<input type="text" class="form-control" name="q" id="q" placeholder="Cari...">
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
					</span>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Data Pemohon Rekomendasi</h2>
				</header>
				<div class="panel-body">
					<table class="table table-hover table-striped mb-none" id="listpemohon">
						<thead>
							<tr>
								<th width="5%">No</th>
								<th>Nama Perusahaan/Perseorangan</th>
								<th width="20%">Tanggal Registrasi</th>
								<th width="20%">Pengajuan Terakhir</th>
								<th width="15%">Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
			</section>
		</div>
	</div>
</section>
<?php
include(AdminFooter);
?>