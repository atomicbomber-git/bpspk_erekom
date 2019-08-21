<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav-peg').addClass('nav-active');
});
</script>
<script src=\"custom.js\"></script>
";
include("function.php");
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Manajemen User <a href="add.php" class="mb-xs mt-xs mr-xs btn btn-warning btn-xs"><i class="fa fa-plus"></i> User Baru</a></h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Users</span></li>
				<li><span>Manajemen User</span></li>
			</ol>
	
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<input type="hidden" name="filterlvl" id="filterlvl" value="all">
		<div class="col-md-9">
			<form action="" method="POST" id="form_filter" id="form_filter" class="form-inline">
			<div class="form-group">
				<select name="satker" id="satker" class="form-control input-sm mb-md">
					<option value="all">Semua</option>
					<?php
					$sql->get_all('ref_satuan_kerja');
					if($sql->num_rows>0){
						foreach($sql->result as $row){
							echo '<option value="'.$row['id_satker'].'">'.$row['nm_satker'].'</option>';
						}
					}
					?>
				</select>
				<button type="submit" class="btn btn-sm mb-md btn-default">Filter</button>
			</div>
		</form>
		</div>
		<div class="col-md-3">
			<div class="row">
				<div class="col-md-12">
					<form action="" method="POST" id="form_cari" name="form_cari" class="search">
						<div class="input-group input-search">
							<input type="text" class="form-control" name="q" id="q" placeholder="Cari...">
							<span class="input-group-btn">
								<button class="btn btn-default" type="submit">
									<i class="fa fa-search"></i>
								</button>
							</span>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<h2 class="panel-title">Manajemen User</h2><em class="panel-subtitle ulvl"></em>
				</header>
				<div class="panel-body">
					<table class="table table-hover table-striped" id="tblistpegawai">
						<thead>
							<tr>
								<th>#</th>
								<th>Nama Lengkap</th>
								<th width="25%">Jabatan</th>
								<th width="25%">Satuan Kerja</th>
								<th>Email/No Telp</th>
							</tr>
						</thead>
					</table>
				</div>
				<div id="DelModal" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Hapus Data?</h2>
						</header>
						<div class="panel-body">
							<div class="modal-wrapper">
								<div class="modal-icon">
									<i class="fa fa-question-circle"></i>
								</div>
								<div class="modal-text">
									<p>Apakah anda yakin akan menghapus Data ini?</p>
								</div>
							</div>
						</div>
						<footer class="panel-footer">
							<div class="row">
								<div class="col-md-12 text-right">
									<button id="del_pid" data-id="" class="btn btn-primary modal-confirm">Confirm</button>
									<button class="btn btn-default modal-dismiss">Cancel</button>
								</div>
							</div>
						</footer>
					</section>
				</div>
			</section>
		</div>
	</div>
</section>
<?php
@include(AdminFooter);
?>