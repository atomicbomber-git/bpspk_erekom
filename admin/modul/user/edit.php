<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav-user').addClass('nav-expanded nav-active');
	$('nav li.user-adm').addClass('nav-active');
});
</script>
<script src=\"custom.js\"></script>
";
include("function.php");
?>
<section role="main" class="content-body">
<header class="page-header">
	<h2>Edit Profil User</h2>
	<div class="right-wrapper pull-right">
		<ol class="breadcrumbs">
			<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
			<li><a href="./">Users</a></li>
			<li><span>Edit</span></li>
		</ol>
		<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
	</div>
</header>
<?php
$sql->get_row("op_user",
	array('idu'=>$_GET['landing']),
	array('username', 'nm_lengkap', 'email', 'status', 'lvl','jabatan','no_telp'));
$row=$sql->result;
?>
<form method='post' id="update_user" name="update_user" action='' class="form-horizontal form-bordered">
	<input type="hidden" name="a" value="update">
	<input type="hidden" name="i" value="<?php echo $_GET['landing'];?>">
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading panel-featured-left">
					<h2 class="panel-title">User Profile</h2>
				</header>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-md-3 control-label" for="username">Username</label>
						<div class="col-md-3">
							<input type="text" class="form-control" id="username" name="username" readonly value="<?php echo $row['username'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label" for="nama_lengkap">Nama Lengkap</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo $row['nm_lengkap'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label" for="email">Email</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="email" name="email" value="<?php echo $row['email'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label" for="jabatan">Jabatan</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="jabatan" name="jabatan" value="<?php echo $row['jabatan'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label" for="no_telp">No Telp</label>
						<div class="col-md-4">
							<input type="text" class="form-control" id="no_telp" name="no_telp" value="<?php echo $row['no_telp'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label" for="password">Password Baru</label>
						<div class="col-md-3">
							<input type="password" class="form-control" id="pwd" name="pwd">
						</div>
						<div class="col-md-4">
							<em>Isi Jika Ingin Mengganti Password</em>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label" for="repeat_pwd">Ulangi Password Baru</label>
						<div class="col-md-3">
							<input type="password" class="form-control" id="repeat_pwd" name="repeat_pwd">
						</div>
					</div>
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

							echo pilihan("hak_akses",$array_level,$row['lvl'],"class='form-control' id='hak_akses'");
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label" for="">Status </label>
						<div class="col-md-2">
							<select name="user_status" id="user_status" class="form-control">
								<option value="1" <?php echo ($row['status']=='1')?"selected":"";?>>Non Aktif</option>
								<option value="2" <?php echo ($row['status']=='2')?"selected":"";?>>Aktif</option>
							</select>
						</div>
					</div>
				</div>
				<footer class="panel-footer">
					<div class="form-group">
						<div class="col-md-3">
							<a href="./" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
						</div>
						<div class="col-md-3">
							<input type="submit" class="btn btn-primary" id="btn_update" name="btn_update" value="Update User">
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