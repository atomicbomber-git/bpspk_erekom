<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav-user').addClass('nav-active');
	$('.listpegawai').select2();
});
</script>
<script src=\"custom.js\"></script>
";
include("function.php");
?>
<section role="main" class="content-body">
<header class="page-header">
	<h2>Tambah User Baru</h2>
	<div class="right-wrapper pull-right">
		<ol class="breadcrumbs">
			<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
			<li><a href="./">Users</a></li>
			<li><span>Add</span></li>
		</ol>
		<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
	</div>
</header>

<form method='post' id="add_user" name="add_user" action='' class="form-horizontal form-bordered">
	<input type="hidden" name="a" value="add">
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading panel-featured-left">
					<h2 class="panel-title">Profil User</h2>
				</header>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-3 control-label" for="hak akses">Hak Akses</label>
						<div class="col-md-3">
							<?php
							$array_level=array(
								""=>"Pilih Level",
						        "100"=>"Admin",
						        "90"=>"Kepala Balai",
						        "91"=>"Plh Kepala Balai",
						        "95"=>"Verifikator"
						        );

							echo pilihan("hak_akses",$array_level,"","class='form-control' id='hak_akses'");
							?>
						</div>
					</div>
					<div class="form-group kep-group">
						<label class="col-md-3 control-label">Pegawai</label>
						<div class="col-md-6">
							<select name="pegawai" class="listpegawai form-control">
								<option value="">-Pilih-</option>
								<?php
								$sql->get_all('op_pegawai',array('status'=>2),array('idp','nip','nm_lengkap'));
									if($sql->num_rows>0){
										foreach($sql->result as $ptgs){
											echo '<option value="'.$ptgs['nip'].'">'.$ptgs['nip'].' - '.$ptgs['nm_lengkap'].'</option>';
										}	
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label" for="username">Username</label>
						<div class="col-md-3">
							<input type="text" class="form-control" id="username" name="username">
						</div>
					</div>
					<div class="form-group admin-group">
						<label class="col-md-3 control-label" for="nama_lengkap">Nama Lengkap</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap">
						</div>
					</div>
					<div class="form-group admin-group">
						<label class="col-md-3 control-label" for="email">Email</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="email" name="email" >
						</div>
					</div>
					<div class="form-group admin-group">
						<label class="col-md-3 control-label" for="jabatan">Jabatan</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="jabatan" name="jabatan" >
						</div>
					</div>
					<div class="form-group admin-group">
						<label class="col-md-3 control-label" for="no_telp">No Telp</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="no_telp" name="no_telp" >
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label" for="password">Password</label>
						<div class="col-md-4">
							<input type="password" class="form-control" id="pwd" name="pwd">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label" for="repeat_pwd">Ulangi Password</label>
						<div class="col-md-4">
							<input type="password" class="form-control" id="repeat_pwd" name="repeat_pwd">
						</div>
					</div>
					
				</div>
				<footer class="panel-footer">
					<div class="form-group">
						<div class="col-md-3">
							<a href="./" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
						</div>
						<div class="col-md-3">
							<input type="submit" class="btn btn-primary" id="btn_add" name="btn_add" value="Tambah User Baru">
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