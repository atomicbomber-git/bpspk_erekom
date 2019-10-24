<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav1').addClass('nav-active');
	$('.plhn-petugas').select2();
	$('.img-prev').magnificPopup({type:'image'});
});
</script>
<script src=\"custom-1.js\"></script>
";

$idpengajuan=base64_decode($_GET['data']);

if(!ctype_digit($idpengajuan)){
	exit();
}

$dtph=$sql->run("SELECT * FROM tb_permohonan WHERE status IN (1,3) AND idp='$idpengajuan' LIMIT 1");
if($dtph->rowCount()>0){
$p=$dtph->fetch();
$arr_alatangkut=array(
	'udara'=>"Pesawat Udara",
	'laut'=>"Kapal Laut",
	'darat'=>"Kendaraan Darat");

$arr_jns_tujuan=array(
	"perdagangan"=>"Perdagangan",
	"souvenir"=>"Souvenir");
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Verifikasi Data</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Rekomendasi</span></li>
				<li> <a href="./list-permohonan-masuk.php"><span>Permohonan Masuk</span></a></li>
				<li><span>Verifikasi Data</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-7">
			<?php
			$b=$sql->run("SELECT u.nama_lengkap,u.email, b.* FROM tb_userpublic u JOIN tb_biodata b ON (b.ref_iduser=u.iduser) WHERE u.iduser='".$p['ref_iduser']."' LIMIT 1");
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
							<td width="20%">Nama Perusahaan/Perseorangan</td>
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
			</section>
		</div>
		<div class="col-md-5">
			<section class="panel">
				<header class="panel-heading">
					<div class="panel-actions"><a href="#" class="fa fa-caret-down"></a></div>
					<h2 class="panel-title">Berkas Pemohon</h2>
				</header>
				<div class="panel-body">
					<table class="table">
						<tr>
							<td>KTP<br/>
							<?php
							$n=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$p['ref_iduser']."' AND jenis_berkas='4' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
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
							$n=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$p['ref_iduser']."' AND jenis_berkas='2' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
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
							$sql->get_row('tb_berkas',array('ref_iduser'=>$p['ref_iduser'],'jenis_berkas'=>3),array('nama_file'));
							$sql->order_by="revisi DESC, date_upload DESC, idb DESC";
							$img_siup=$sql->result;
							if($img_siup['nama_file']==''){
								echo '<p class="text-alert alert-warning">SIUP Belum diupload</p>';
							}else{
								echo '<img width="50%" href="'.BERKAS.$img_siup['nama_file'].'" src="'.BERKAS.$img_siup['nama_file'].'" class="img-prev">';
							}
							?></td>
						</tr>

						<tr>
							<td>NIB<br/>
							<?php
							$sql->get_row('tb_berkas',array('ref_iduser'=>$p['ref_iduser'],'jenis_berkas'=>3),array('nama_file'));
							$sql->order_by="revisi DESC, date_upload DESC, idb DESC";
							$img_nib=$sql->result;
							if($img_nib['nama_file']==''){
								echo '<p class="text-alert alert-warning">SIUP Belum diupload</p>';
							}else{
								echo '<img width="50%" href="'.BERKAS.$img_nib['nama_file'].'" src="'.BERKAS.$img_nib['nama_file'].'" class="img-prev">';
							}
							?></td>
						</tr>

						<tr>
							<td>SIPJI<br/>
							<?php
							$sql->get_row('tb_berkas',array('ref_iduser'=>$p['ref_iduser'],'jenis_berkas'=>3),array('nama_file'));
							$sql->order_by="revisi DESC, date_upload DESC, idb DESC";
							$img_sipji=$sql->result;
							if($img_sipji['nama_file']==''){
								echo '<p class="text-alert alert-warning">SIUP Belum diupload</p>';
							}else{
								echo '<img width="50%" href="'.BERKAS.$img_sipji['nama_file'].'" src="'.BERKAS.$img_sipji['nama_file'].'" class="img-prev">';
							}
							?></td>
						</tr>

						<tr>
							<td>TTD<br/>
							<?php
							$t=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$p['ref_iduser']."' AND jenis_berkas='1' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
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
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<div class="panel-actions"><a href="#" class="fa fa-caret-down"></a></div>
					<h2 class="panel-title">Data Permohonan & Barang</h2>
				</header>
				<div class="panel-body">
					<table class="table table-hover">
						<tr>
							<td width="20%">Diajukan Pada</td>
							<td><?php echo tanggalIndo($p['tgl_pengajuan'],'j F Y H:i');?></td>
						</tr>
						<tr>
							<td>No Antrian</td>
							<td><?php echo format_noantrian($p['tgl_pelayanan'],$p['no_antrian']);?></td>
						</tr>
						<tr>
							<td>Dikirim Ke</td>
							<td><?php echo $p['tujuan'];?></td>
						</tr>
						<tr>
							<td>Penerima</td>
							<td><?php echo $p['penerima'];?></td>
						</tr>
						<tr>
							<td>Untuk</td>
							<td><?php echo $arr_jns_tujuan[$p['jenis_tujuan']];?></td>
						</tr>
						<tr>
							<td>Alat Angkut</td>
							<td><?php echo $arr_alatangkut[$p['jenis_angkutan']];?></td>
						</tr>
						<tr>
							<td>Alamat Pemeriksaan</td>
							<td><?php echo $p['alamat_gudang'];?></td>
						</tr>
						<tr>
							<td>Keterangan Tambahan</td>
							<td><?php echo $p['ket_tambahan'];?></td>
						</tr>
					</table>
					<hr/>
					<table class="table table-hover table-bordered">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Barang</th>
								<th>Kuantitas</th>
								<th>Jumlah Berat</th>
								<th>Asal Komoditas</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql->order_by=""; 
							$sql->get_all('tb_barang',array('ref_idphn'=>$idpengajuan),'*');
							if($sql->num_rows>0){
								$no=0;
								foreach($sql->result as $b){
									$no++;
									echo '<tr>
										<td>'.$no.'</td>
										<td>'.$b['nm_barang'].'</td>
										<td>'.$b['kuantitas'].' <em>Colly</em></td>
										<td>'.$b['jlh'].' Kg</td>
										<td>'.$b['asal_komoditas'].'</td>
									</tr>';
								}
							}
							?>
						</tbody>
					</table>
					<hr/>
					<table class="table">
						<tr>
							<td colspan="2" class="text-center">Verifikasi Data</td>
						</tr>
						<tr>
							<td width="50%">
								<p>Jika Data Yg Diajukan Tidak Lengkap Anda Dapat Menolak, dan meminta <strong>Pemohon</strong> Memperbaiki data.</p>
								<div class="col-md-12">
									<form method="post" class="form-horizontal" id="form-penolakan">
										<input type="hidden" name="idp" value="<?php echo base64_encode($idpengajuan);?>" >
										<input type="hidden" name="a" value="dt-tolak" >
										<div class="form-group">
											<textarea class="form-control" name="isi_pesan" rows="5"></textarea>
										</div>
										<div class="form-group">
											<button type="submit" id="btn-tolak" class="btn btn-sm btn-danger">Tolak & Kirim Pesan</button>
										</div>
									</form>
								</div>
							</td>
							<td width="50%"><p>Data diterima.</p>
							<div class="col-md-12">
								<form id="form-penerimaan" method="post" class="form-horizontal">
									<input type="hidden" name="idp" value="<?php echo base64_encode($idpengajuan);?>" >
									<input type="hidden" name="a" value="dt-terima" >
									<div class="form-group">
										<p>Pilih Satuan Kerja</p>
										<select name="pil_satker" id="pil_satker" class='form-control'>
											<option value="">-Pilih Satker-</option>
											<?php
											$sql->get_all('ref_satuan_kerja',array(),'*');
											if($sql->num_rows>0){
												foreach ($sql->result as $r) {
													echo '<option value="'.$r['id_satker'].'">'.$r['nm_satker'].'</option>';
												}
											}
											?>
										</select>
									</div>

									<div class="form-group">
										<label for="tanggal"> Tanggal: </label>
										<input class="form-control" name="tanggal" type="date">
									</div>

									<div class="form-group">
									Pilih Petugas Pemeriksaan<br>
									</div>
										<?php
										$jlhpegawai=$sql->get_count('op_pegawai',array('status'=>2));
										if($jlhpegawai>0){
											$count=(($jlhpegawai>6)?7:$jlhpegawai);
											for($x=0;$x<$count;$x++){
												$sql->get_all('op_pegawai',array('status'=>2),array('idp','nip','nm_lengkap'));
												if($sql->num_rows>0){
													echo '
													<div class="form-group">
													<select class="form-control plhn-petugas" name="petugas[]">
													<option value="">- Pilih Petugas -</option>';
													foreach($sql->result as $ptgs){
														echo '<option value="'.$ptgs['idp'].'">'.$ptgs['nip'].' - '.$ptgs['nm_lengkap'].'</option>';
													}	
													echo '</select></div>';
												}
											}
										}
										?>
									<div class="form-group">
										<button type="submit" id="btn-terima" class="btn btn-sm btn-primary">Terima & Lanjutkan</button></div>
								</form>
							</div>
							</td>
						</tr>
					</table>
				</div>
			</section>
		</div>
	</div>
</section>
<?php
}
include(AdminFooter);
?>