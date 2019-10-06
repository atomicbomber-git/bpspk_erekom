<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.navph').addClass('nav-active');
	$('.img-prev').magnificPopup({type:'image'});
});
</script>
<script src=\"custom.js\"></script>
";

$iduser=base64_decode($_GET['data']);
if(!ctype_digit($iduser)){
	exit();
}

?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Biodata</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li> <a href="./list.php"><span>Data Pemohon</span></a></li>
				<li><span>Biodata</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-7">
			<?php
			$b=$sql->run("SELECT u.nama_lengkap,u.email, b.* FROM tb_userpublic u JOIN tb_biodata b ON (b.ref_iduser=u.iduser) WHERE u.iduser='".$iduser."' LIMIT 1");
			$bio=$b->fetch();
			?>
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<div class="panel-actions"><a href="#" class="fa fa-caret-down"></a></div>
					<h2 class="panel-title">Profil Pemohon</h2>
				</header>
				<div class="panel-body">
					<table class="table table-hover">
						<tr>
							<td width="20%">Nama Lengkap</td>
							<td><?php echo $bio['nama_lengkap'];?></td>
						</tr>
						<tr>
							<td>Tempat, Tanggal Lahir</td>
							<td><?php echo $bio['tmp_lahir'].", ".tanggalIndo($bio['tgl_lahir'],'j F Y');?></td>
						</tr>
						<tr>
							<td>No Identitas</td>
							<td><?php echo $bio['no_ktp'];?></td>
						</tr>
						<tr>
							<td>Alamat Rumah</td>
							<td><?php echo $bio['alamat'];?></td>
						</tr>
						<tr>
							<td>No Telepon</td>
							<td><?php echo $bio['no_telp'];?></td>
						</tr>
						<tr>
							<td>Email</td>
							<td><?php echo $bio['email'];?></td>
						</tr>
						<tr>
							<td>NPWP</td>
							<td><?php echo $bio['npwp'];?></td>
						</tr>
						<tr>
							<td>Nama Perusahaan</td>
							<td><?php echo $bio['nm_perusahaan'];?></td>
						</tr>
						<tr>
							<td>SIUP</td>
							<td><?php echo $bio['siup'];?></td>
						</tr>
						<tr>
							<td>NIB</td>
							<td><?php echo $bio['nib'];?></td>
						</tr>
						<tr>
							<td>SIPJI</td>
							<td><?php echo $bio['sipji'];?></td>
						</tr>
						<tr>
							<td>Izin Usaha Lainnya</td>
							<td><?php echo $bio['izin_lain'];?></td>
						</tr>
					</table>
				</div>
				<footer class="panel-footer">
					<a href="update.php?p=<?php echo base64_encode($iduser);?>" class="btn btn-sm btn-primary">Edit Biodata</a>
					<!-- <a href="#reset-confirm" class="btn btn-sm btn-danger">Reset Password</a> -->
				</footer>
			</section>
		</div>
		<div class="col-md-5">
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Berkas Pemohon</h2>
				</header>
				<div class="panel-body">
					<table class="table">
						<tr>
							<td>KTP<br/>
							<?php
							$n=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$iduser."' AND jenis_berkas='4' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
							if($n->rowCount()>0){
								$img_npwp=$n->fetch();
								echo '<img width="50%" href="'.BERKAS.$img_npwp['nama_file'].'" src="'.BERKAS.$img_npwp['nama_file'].'" class="img-prev">';
							}else{
								echo '<p class="text-alert alert-warning">KTP Belum diupload</p>';
							}
							?></td>
						</tr>
						<tr>
							<td>NPWP<br/>
							<?php
							$n=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$iduser."' AND jenis_berkas='2' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
							if($n->rowCount()>0){
								$img_npwp=$n->fetch();
								echo '<img width="50%" href="'.BERKAS.$img_npwp['nama_file'].'" src="'.BERKAS.$img_npwp['nama_file'].'" class="img-prev">';
							}else{
								echo '<p class="text-alert alert-warning">NPWP Belum diupload</p>';
							}
							?></td>
						</tr>
						<tr>
							<td>SIUP<br/>
							<?php
							$s=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$iduser."' AND jenis_berkas='3' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
							if($s->rowCount()>0){
								$img_siup=$s->fetch();
								echo '<img width="50%" href="'.BERKAS.$img_siup['nama_file'].'" src="'.BERKAS.$img_siup['nama_file'].'" class="img-prev">';
							}else{
								echo '<p class="text-alert alert-warning">SIUP Belum diupload</p>';
							}

							?></td>
						</tr>

						<tr>
							<td>NIB<br/>
							<?php
							$s=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$iduser."' AND jenis_berkas='5' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
							if($s->rowCount()>0){
								$img_nib=$s->fetch();
								echo '<img width="50%" href="'.BERKAS.$img_nib['nama_file'].'" src="'.BERKAS.$img_nib['nama_file'].'" class="img-prev">';
							}else{
								echo '<p class="text-alert alert-warning">NIB Belum diupload</p>';
							}

							?></td>
						</tr>

						<tr>
							<td>SIPJI<br/>
							<?php
							$s=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$iduser."' AND jenis_berkas='6' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
							if($s->rowCount()>0){
								$img_sipji=$s->fetch();
								echo '<img width="50%" href="'.BERKAS.$img_sipji['nama_file'].'" src="'.BERKAS.$img_sipji['nama_file'].'" class="img-prev">';
							}else{
								echo '<p class="text-alert alert-warning">SIPJI Belum diupload</p>';
							}

							?></td>
						</tr>

						<tr>
							<td>TTD<br/>
							<?php
							$t=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$iduser."' AND jenis_berkas='1' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
							if($t->rowCount()>0){
								$img_npwp=$t->fetch();
								echo '<img width="50%" href="'.BERKAS.$img_npwp['nama_file'].'" src="'.BERKAS.$img_npwp['nama_file'].'" class="img-prev">';
							}else{
								echo '<p class="text-alert alert-warning">Tandatangan Belum diupload</p>';
							}
							?></td>
						</tr>
					</table>
				</div>
			</section>
		</div>
	</div>
	<div class="row">
		<input type="hidden" id="u" value="<?php echo $iduser;?>">
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Riwayat Permohonan Rekomendasi</h2>
				</header>
				<div class="panel-body">
					<table class="table table-hover table-striped mb-none" id="listriwayat">
						<thead>
							<tr>
								<th width="5%">No</th>
								<th>Tujuan Pengiriman</th>
								<th width="20%">Tanggal Pengajuan</th>
								<th width="10%">No Antrian</th>
								<th width="25%">Status</th>
								<th width="17%">Aksi</th>
							</tr>
						</thead>
					</table>
				</div>
			</section>
		</div>
		<div class="col-md-12">
			<section class="panel">
				<header class="panel-heading">
					<h2 class="panel-title">Riwayat Pengisian Kuisioner</h2>
				</header>
				<div class="panel-body">
					<table class="table table-hover table-striped mb-none" id="listkuisioner">
						<thead>
							<tr>
								<th width="5%">No</th>
								<th width="20%">Tanggal Pengisian</th>
								<th width="10%">Diisi Pada Permohonan Ke</th>
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