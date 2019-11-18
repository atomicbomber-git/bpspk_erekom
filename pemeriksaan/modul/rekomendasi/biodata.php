<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('ul li.nav-profil').addClass('active');
	$('.img-prev').magnificPopup({type:'image'});
});
</script>
";

$idpengajuan=U_IDP;
if(!ctype_digit($idpengajuan)){
	exit();
}

$sql->get_row('tb_permohonan',array('idp'=>$idpengajuan,'status'=>2),'*');
if($sql->num_rows>0){
$p=$sql->result;
$arr_alatangkut=array(
	'udara'=>"Pesawat Udara",
	'laut'=>"Kapal Laut",
	'darat'=>"Kendaraan Darat");
$arr_jns_tujuan=array(
	"perdagangan"=>"Perdagangan",
	"souvenir"=>"Souvenir");
?>
<body class="hold-transition skin-blue sidebar-mini">

<div class="content-wrapper">
    <section class="content-header">
      <h1>
        Biodata Pemohon
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo c_URL;?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Biodata Pemohon</li>
      </ol>
    </section>
    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
				<div class="box">
			        <div class="box-header with-border">
			          <h3 class="box-title">Data Permohonan</h3>
			        </div>
			        <div class="box-body">
						<table class="table table-hover">
							<tr>
								<td width="20%">Diajukan Pada</td>
								<td>: <?php echo tanggalIndo($p['tgl_pengajuan'],'j F Y H:i');?></td>
							</tr>
							<tr>
								<td>Dikirim Ke</td>
								<td>: <?php echo $p['tujuan'];?></td>
							</tr>
							<tr>
								<td>Penerima</td>
								<td>: <?php echo $p['penerima'];?></td>
							</tr>
							<tr>
								<td>Untuk</td>
								<td>: <?php echo $arr_jns_tujuan[$p['jenis_tujuan']];?></td>
							</tr>
							<tr>
								<td>Alat Angkut</td>
								<td>: <?php echo $arr_alatangkut[$p['jenis_angkutan']];?></td>
							</tr>
							<tr>
								<td>Alamat Pemeriksaan</td>
								<td>: <?php echo $p['alamat_gudang'];?></td>
							</tr>
							<tr>
								<td>Keterangan Tambahan</td>
								<td>: <?php echo $p['ket_tambahan'];?></td>
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
									$barangs = App\Models\Barang::query()
										->where("ref_idphn", $idpengajuan)
										->with("satuan_kuantitas")
										->get();
								?>

								<?php foreach($barangs as $index => $barang): ?>
								<tr>
									<td>  <?= $index + 1 ?> </td>
									<td>  <?= $barang->nm_barang ?> </td>
									<td>  <?= $barang->kuantitas ?> <?= $barang->satuan_kuantitas->nama ?> </td>
									<td>  <?= $barang->jlh ?> </td>
									<td>  <?= $barang->asal_komoditas ?> </td>
								</tr>
								<?php endforeach ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-7">
				<?php
				$b=$sql->run("SELECT u.nama_lengkap,u.email, b.* FROM tb_userpublic u JOIN tb_biodata b ON (b.ref_iduser=u.iduser) WHERE u.iduser='".$p['ref_iduser']."' LIMIT 1");
				$bio=$b->fetch();
				?>
				 <div class="box">
			        <div class="box-header with-border">
			          <h3 class="box-title">Profil Pemohon</h3>
			        </div>
			        <div class="box-body">
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

							<tr>
								<td>NIB</td>
								<td><?php echo $bio['nib'];?></td>
							</tr>
							<tr>


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
				</div>
			</div>
			<div class="col-md-5">
				 <div class="box">
			        <div class="box-header with-border">
			          <h3 class="box-title">Berkas Permohonan</h3>
			        </div>
			        <div class="box-body">
						<table class="table">
							<tr>
								<td>KTP<br/>
								<?php
								$n=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$p['ref_iduser']."' AND jenis_berkas='4' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
								if($n->rowCount()>0){
									$img_ktp=$n->fetch();
									echo '<img width="50%" href="'.BERKAS.$img_ktp['nama_file'].'" src="'.BERKAS.$img_ktp['nama_file'].'" class="img-prev">';
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
								$n=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$p['ref_iduser']."' AND jenis_berkas='3' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
								if($n->rowCount()>0){
									$img_siup=$n->fetch();
									echo '<img width="50%" href="'.BERKAS.$img_siup['nama_file'].'" src="'.BERKAS.$img_siup['nama_file'].'" class="img-prev">';
								}else{
									echo '<p class="text-alert alert-warning">SIUP Belum diupload</p>';
								}
								?></td>
							</tr>

							<tr>
								<td>NIB<br/>
								<?php
								$n=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$p['ref_iduser']."' AND jenis_berkas='5' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
								if($n->rowCount()>0){
									$img_nib=$n->fetch();
									echo '<img width="50%" href="'.BERKAS.$img_nib['nama_file'].'" src="'.BERKAS.$img_nib['nama_file'].'" class="img-prev">';
								}else{
									echo '<p class="text-alert alert-warning">NIB Belum diupload</p>';
								}
								?></td>
							</tr>

							<tr>
								<td>SIPJI<br/>
								<?php
								$n=$sql->run("SELECT nama_file FROM tb_berkas WHERE ref_iduser='".$p['ref_iduser']."' AND jenis_berkas='6' ORDER BY revisi DESC, date_upload DESC LIMIT 1");
								if($n->rowCount()>0){
									$img_sipji=$n->fetch();
									echo '<img width="50%" href="'.BERKAS.$img_sipji['nama_file'].'" src="'.BERKAS.$img_sipji['nama_file'].'" class="img-prev">';
								}else{
									echo '<p class="text-alert alert-warning">SIPJI Belum diupload</p>';
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
				</div>
			</div>
		</div>
	</section>

</div>
</body>
<?php
}
include(AdminFooter);
?>
