<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav3').addClass('nav-active');
});
</script>
<script src=\"custom-3.js\"></script>
";
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Daftar Permohonan Rekomendasi</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Rekomendasi</span></li>
				<li><span>Daftar Permohonan</span></li>
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
					<h2 class="panel-title">Permohonan Masuk</h2>
				</header>
				<div class="panel-body">
					<table class="table table-hover table-striped mb-none" id="list-persetujuankepala">
						<thead>
							<tr>
								<th width="5%">No</th>
								<th>No Antrian</th>
								<th>Nama Perusahaan/Perseorangan</th>
								<th width="20%">Tanggal Pengajuan</th>
								<th width="20%">Tanggal Pemeriksaan</th>
								<th>Tujuan</th>
								<th width="17%">Aksi</th>
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