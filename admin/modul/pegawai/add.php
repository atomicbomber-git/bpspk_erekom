<?php
include ("config.php");

$SCRIPT_FOOT = "
	<script>
	$(document).ready(function(){
		$('nav li.nav-peg').addClass('nav-active');
	});
	</script>
	<script src=\"custom.js\"></script>
";

?>
<section role="main" class="content-body">
<header class="page-header">
	<h2>Tambah Data Pegawai Baru</h2>
	<div class="right-wrapper pull-right">
		<ol class="breadcrumbs">
			<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
			<li><a href="./">Data Pegawai</a></li>
			<li><span>Tambah Data Pegawai Baru</span></li>
		</ol>
		<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
	</div>
</header>
<form id="pegawai_add" method='post' action='' class="form-horizontal" enctype="multipart/form-data">
	<input type="hidden" name="a" value="add">
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured-primary">
				<header class="panel-heading panel-featured-left">
					<div class="panel-actions">
						<a href="#" class="fa fa-caret-down"></a>
					</div>
					<h2 class="panel-title">Data Pegawai</h2>
				</header>
				<div class="panel-body">		
					<div class="form-group">
						<label class="col-md-3 control-label">Nama Lengkap</label>
						<div class="col-md-6">
							<input type="text" name="nm_lengkap" id="nm_lengkap" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">NIP</label>
						<div class="col-md-3">
							<input type="text" name="nip" id="nip" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Jabatan</label>
						<div class="col-md-8">
							<input type="text" name="jabatan" id="jabatan" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">No Telp</label>
						<div class="col-md-3">
							<input type="text" name="no_telp" id="no_telp" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Email</label>
						<div class="col-md-6">
							<input type="text" name="email" id="email" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Satuan Kerja</label>
						<div class="col-md-4">
							<select name="satker" id="satker" class="form-control">
								<option value="">-Pilih-</option>
								<?php
								$sql->get_all('ref_satuan_kerja');
								if($sql->num_rows>0){
									foreach ($sql->result as $r) {
										echo '<option value="'.$r['id_satker'].'">'.$r['nm_satker'].'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">File Scan Tandatangan</label>
						<div class="col-md-5">
							<input type="file" name="file_ttd" id="file_ttd" class="form-control">
							<p class="text-alert alert-danger">Gambar png/jpg/jpeg, maksimal 500 KB</p>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="form-group">
						<div class="col-md-3">
							<a href="./" class="btn btn-default">
								<i class="fa fa-arrow-left"></i> Kembali
							</a>
						</div>
						<div class="col-md-9">
							<button type="submit" id="btn_simpan" class="btn btn-primary btn_simpan ">
								<i class="fa fa-paper-plane"></i> Tambah Data 
							</button>
							<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
						</div>
					</div>
				</footer>
			</section>
		</div>
	</div>
</form>
</section>
<?php
include(AdminFooter);
?>