<?php

use App\Models\Permohonan;
use App\Services\Formatter;

include ("../../engine/render.php");

$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, datepicker3.css, pnotify.custom.css, select2.css, codemirror.css, monokai.css, bootstrap-tagsinput.css, bootstrap-timepicker.css, theme.css, default.css, datatables.css, modernizr.js";

$ITEM_FOOT = "jquery.js, jquery.browser.mobile.js, bootstrap.js, nanoscroller.js, bootstrap-datepicker.js, magnific-popup.js, jquery.placeholde,jquery.dataTables.js,datatables.js, pnotify.custom.js, jquery.appear.js, select2.js, jquery.autosize.js, bootstrap-tagsinput.js, bootstrap-timepicker.js, theme.js, theme.init.js,jquery.validate.js,jquery.validate.msg.id.js ";

require_once(c_THEMES."auth.php");

$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav4').addClass('nav-active');
});
</script>";

$idpengajuan=base64_decode($_GET['permohonan']);
if(!ctype_digit($idpengajuan)){
	exit();
}

$formatter = container(Formatter::class);

/* Controller code */
$formatter = container(App\Services\Formatter::class);
$permohonan = Permohonan::find($idpengajuan);
$tanggal_pemeriksaan = $permohonan->tanggal_pemeriksaan ?
	$formatter->date($permohonan->tanggal_pemeriksaan) :
	'-';

$sql->get_row('tb_permohonan',array('idp'=>$idpengajuan),'*');
if($sql->num_rows>0){
$p=$sql->result;
$arr_alatangkut=array(
	'udara'=>"Pesawat Udara",
	'laut'=>"Kapal Laut",
	'darat'=>"Kendaraan Darat");

$arr_jns_tujuan=array(
	"perdagangan"=>"Perdagangan",
	"souvenir"=>"Souvenir");

$arr_status=array(
	1=>"Pemeriksaan Data.",
	2=>"Data Diterima, Pengajuan Sedang Diproses Oleh Admin.",
	3=>"Data Ditolak, Berkas/Data Tidak Lengkap.",
	4=>"Pemeriksaan Sampel Telah Dilakukan.",
	5=>"Surat Rekomendasi Sudah Diterbitkan."
);
?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Detail Permohonan</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><a href="<?php echo c_URL;?>?riwayat"><span>Riwayat Permohonan Rekomendasi</span></a></li>
				<li><span>Detail Permohonan</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
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
							<td width="20%">No Antrian</td>
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
					<hr/>

					Dijadwalkan tanggal <?= $formatter->fancyDate($tanggal_pemeriksaan) ?> di <?= $permohonan->alamat_gudang ?> <br/>

					Status : <?php echo $arr_status[$p['status']];?> <br/>
					<?php
					if($p['status']=='3'){
						$ps=$sql->run("SELECT pesan FROM tb_hsl_verifikasi WHERE ref_idp='$idpengajuan' ORDER BY date_act DESC LIMIT 1");
						$pesan=$ps->fetch();
						echo '<span class="text-alert alert-danger">'.$pesan['pesan'].'</span>';
					}

					if($p['status']==5){
						$sql->get_row('tb_rekomendasi',array('ref_idp'=>$idpengajuan),array('kode_surat'));
						$rowp=$sql->result;
						echo "<a target='_blank' class='btn btn-sm btn-primary' href='".c_DOMAIN_UTAMA."download.php?surat=".$rowp['kode_surat']."&token=".md5('download'.$rowp['kode_surat'].'public')."'>Download</a>";
					}
					?>
				</div>
			</section>
		</div>
	</div>
</section>
<?php
}
include(AdminFooter);
?>