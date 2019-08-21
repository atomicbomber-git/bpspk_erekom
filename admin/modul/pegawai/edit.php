<?php
include ("config.php");
$id=base64_decode($_GET['landing']);
if(!ctype_digit($id)){
	exit();
}
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
	<h2>Edit Data Pegawai</h2>
	<div class="right-wrapper pull-right">
		<ol class="breadcrumbs">
			<li><a href="<?php echo c_MODULE;?>"><i class="fa fa-home"></i></a></li>
			<li><a href="./">Data Pegawai</a></li>
			<li><span>Edit Data Pegawai</span></li>
		</ol>
		<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
	</div>
</header>
<form id="pegawai_update" method='post' action='' class="form-horizontal" enctype="multipart/form-data">
	<input type="hidden" name="a" value="update">
	<div class="row">
		<div class="col-md-12">
			<?php
			$sql->get_row('op_pegawai',array('idp'=>$id),'*');
			if($sql->num_rows>0){
			$row=$sql->result;
			?>
			<input type="hidden" name="idp" id="idp" value="<?php echo $_GET['landing'];?>">
			<input type="hidden" name="old" id="old" value="<?php echo $row['ttd'];?>">
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
							<input type="text" name="nm_lengkap" id="nm_lengkap" class="form-control" value="<?php echo $row['nm_lengkap'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">NIP</label>
						<div class="col-md-3">
							<input type="text" name="nip" id="nip" class="form-control" value="<?php echo $row['nip'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Jabatan</label>
						<div class="col-md-8">
							<input type="text" name="jabatan" id="jabatan" class="form-control" value="<?php echo $row['jabatan'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">No Telp</label>
						<div class="col-md-3">
							<input type="text" name="no_telp" id="no_telp" class="form-control" value="<?php echo $row['no_telp'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Email</label>
						<div class="col-md-6">
							<input type="text" name="email" id="email" class="form-control" value="<?php echo $row['email'];?>">
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
										if($r['id_satker']==$row['idsatker']){
											echo '<option selected value="'.$r['id_satker'].'">'.$r['nm_satker'].'</option>';
										}else{
											echo '<option value="'.$r['id_satker'].'">'.$r['nm_satker'].'</option>';
										}
									}
								}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Tandatangan</label>
						<div class="col-md-5">
							<img width="80%" src="<?php echo ADM_IMAGES.$row['ttd'];?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"></label>
						<div class="col-md-5">
							<label class="checkbox-inline">
								<input type="checkbox" id="gantittd" name="gantittd" value="yes"> Ganti File Scan Tandatangan
							</label>
							<p class="text-alert alert-danger">Checklist Jika Ingin Mengganti Gambar</p>
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
								<i class="fa fa-paper-plane"></i> Simpan Perubahan 
							</button>
							<span id="actloading" style="display:none"><i class="fa fa-spin fa-spinner"></i> Menyimpan....</span>
						</div>
					</div>
				</footer>
			</section>
			<?php
			}else{

			}
			?>
		</div>
	</div>
</form>
</section>
<?php
include(AdminFooter);
?>