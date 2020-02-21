<?php

use App\Models\Permohonan;
use App\Services\Formatter;
use App\Services\Letter;
use Jenssegers\Date\Date;

include ("../../engine/render.php");

$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, datepicker3.css, pnotify.custom.css, select2.css, codemirror.css, monokai.css, bootstrap-tagsinput.css, bootstrap-timepicker.css, theme.css, default.css, datatables.css, modernizr.js";

$ITEM_FOOT = "jquery.js, jquery.browser.mobile.js, bootstrap.js, nanoscroller.js, bootstrap-datepicker.js, magnific-popup.js, jquery.placeholde,jquery.dataTables.js,datatables.js, pnotify.custom.js, jquery.appear.js, select2.js, jquery.autosize.js, bootstrap-tagsinput.js, bootstrap-timepicker.js, theme.js, theme.init.js,jquery.validate.js,jquery.validate.msg.id.js ";

require_once(c_THEMES."auth.php");

$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.navph').addClass('nav-active');
});
</script>
<script src=\"custom.js?t=".time()."\"></script>
";

$idpengajuan=base64_decode($_GET['data']);
if(!ctype_digit($idpengajuan)){
	exit();
}

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
	1=>"Pemeriksaan Data Oleh Admin.",
	2=>"Data Diterima, Pengajuan Sedang Diproses Oleh Admin.",
	3=>"Data Ditolak, Berkas/Data Tidak Lengkap.",
	4=>"Pemeriksaan Barang/Sampel Telah Dilakukan.",
	5=>"Surat Rekomendasi Sudah Diterbitkan."
);


/* Controller */
$permohonan = Permohonan::find($idpengajuan);
$permohonan->load("nomor_surat");

$letter = container(Letter::class);

?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Detail Permohonan</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li> <a href="./list.php"><span>Data Pemohon</span></a></li>
				<li> <a href="./biodata.php?data=<?php echo $_GET['u'];?>"><span>Biodata</span></a></li>
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
							<td>Tanggal Pemeriksaan</td>
							<td><?php echo tanggalIndo($p['tanggal_pemeriksaan'],'j F Y');?></td>
						</tr>
						<tr>
							<td>Keterangan Tambahan</td>
							<td><?php echo $p['ket_tambahan'];?></td>
						</tr>

						<tr>
							<td>  Nomor BAP </td>
							<td><?= $permohonan->nomor_surat->no_surat_bap ?? '-' ?></td>
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
								<td>  <?= $barang->jlh ?> Kg</td>
								<td>  <?= $barang->asal_komoditas ?> </td>
							</tr>
							<?php endforeach ?>
						</tbody>
					</table>
					<hr/>
					<strong>Status</strong> : <?php echo $arr_status[$p['status']];?> <br/>
					<strong>Petugas Pemeriksa</strong>: <br/>
					<ol id="listpetugas">
						<?php
						$ptgs=$sql->run("SELECT tpl.id_pl, p.idp,p.nm_lengkap,p.nip FROM tb_petugas_lap tpl JOIN op_pegawai p ON(p.idp=tpl.ref_idpeg)WHERE tpl.ref_idp='$idpengajuan'");
						if($ptgs->rowCount()>0){
							foreach($ptgs->fetchAll() as $pp){
								if($p['status']<4){
									$gantiptgs="<a href='#' class='btn btn-xs btn-danger ganti_ptgs' data-id='".$pp['id_pl']."' data-nama='".$pp['nm_lengkap']."'>Ganti Petugas</a>";
								}else{
									$gantiptgs="";
								}
								echo '<li>'.$pp['nm_lengkap']." ".$gantiptgs.'<br/>'.$pp['nip'].'</li>';
							}
						}
						?>
					</ol>

				

					<?php
					if($p['status']>1 AND $p['status']<5){
						echo '<strong>Detail Login</strong>: <br/>';
						echo 'Username : '.$p['log_u'].'<br/>';
						echo 'Password : '.$p['log_p'].'<br/>';
					}
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
				
			

				<?php if($permohonan->hasil_periksa()->count() === 0): ?>
					<div class="panel panel-featured" style="margin-top: 1rem;">
						<div class="panel-body">
							
							<?= $letter->getHeaderContentHtml(ADM_IMAGES) ?>

							


							<table style="width: 100%">
								<tbody>
									<tr>
										<td style="width: 5rem;"> Nomor </td>
										<td>  </td>
										<td> : </td>
										<td> B-<?= $permohonan->nomor_surat->no_surat_rek?? '-' ?> </td>
										<td style="text-align: right">
											<?= Formatter::fancyDate(Date::today()) ?>
										</td>
									</tr>

									<tr>
										<td style="width: 5rem;"> Perihal </td>
										<td>  </td>
										<td> : </td>
										<td> Pengantar Uji DNA </td>
										<td> </td>
									</tr>
								</tbody>
							</table>

							<p>
								Kepada Yth. <br/>
								<strong> Kepala Laboratorium DNA Forensik Lembaga Eijkman </strong>
								Jl. Diponegoro No. 69 Jakarta 10430
							</p>

							<p style="text-indent: 30px">
								Sebelum telah dilakukannya pemeriksaan terhadap produk yang akan diekspor oleh:
							</p>
							
							<table style="width: 100%">
								<thead>
									<tr>
										<th style="width: 15rem;"></th>
										<th style="width: 1rem;"></th>
										<th></th>
									</tr>
								</thead>

								<tbody>
									<tr>
										<td> Nama Perusahaan </td>
										<td> : </td>
										<td> <?= $permohonan->user->nama_lengkap ?> </td>
									</tr>

									<tr>
										<td> Alamat </td>
										<td> : </td>
										<td> <?= $permohonan->user->biodata->gudang_1 ?> </td>
									</tr>

									<tr>
										<td> Bentuk Produk </td>
										<td> : </td>
										<td> <?= $permohonan->user->hasil_periksa->produk ?>  </td>
									</tr>
								
								</tbody>
							</table>

							<p>
								Dengan ini Loka PSPL Serang belum dapat mengeluarkan rekomendasi dikarenakan produk dimaksud belum dapat diidentifikasi secara uji visual.
							</p>

							<p style="text-indent: 30px">
								Berkaitan dengan hal tersebut, maka dengan ini kami sampaikan permohonan uji DNA produk tersebut untuk membuktikan apakah sampel yang disampaikan adalah termasuk / tidak termasuk jenis dilindungi dan Apendiks CITES (Pristis Microdon, Rhincodon typus, Manta alfredi, Manta birostris, Sphyrna zygaena, Sphyrna lewini, Sphyrna mokarran, Carcharhinus longimanus)
							</p>

							<p>
								Demikian kami sampaikan, atas kerjasamanya kami ucapkan terima kasih.
							</p>

							<div style="text-align: right">
								<table style="
									display: inline-block;
									width: 30rem;
								">
									<tbody>
										<tr>
											<td> Kepala Loka PSPL Serang </td>
										</tr>

										<tr style="height: 10rem;">
										</tr>

										<tr>
											<td>
											Sy. Iwan Taruna Alkadrie, ST., M.Si
											</td>
										</tr>
									</tbody>
								</table>
							</div>

							

							<p> Tembusan Yth: </p>
							<ol>
								<li> Direktur Konservasi dan Keanekaragaman Hayati Laut </li>
								<li> Direktur Utama <?= $permohonan->user->nama_lengkap ?> </li>
							</ol>


						</div>
					</div>
					</div>
				<?php endif ?>

				<footer class="panel-footer">
					<?php if($_GET['ref']!='' AND $_GET['ref']=='stat_permohonan') {
						?>
						<a href="../rekomendasi/stat-permohonan.php" class="btn btn-sm btn-primary">Kembali</a>
						<?php
					}else{
						?>
						<a href="./biodata.php?data=<?php echo $_GET['u'];?>" class="btn btn-sm btn-primary">Kembali</a>
						<?php
					}
					?>
				</footer>
			</section>
		</div>
	</div>
</section>
<div id="GantiPeg" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
	<form id="formgantipeg" method="post">
		<input type="hidden" name="a" id="aksi" value="gantipegpem">
		<input type="hidden" name="idp" id="aksi" value="<?php echo $idpengajuan;?>">
		<input type="hidden" name="idtb" id="idtb" value="">
		<section class="panel">
			<header class="panel-heading">
				<h2 class="panel-title">Ganti Pegawai Pemeriksa</h2>
			</header>
			<div class="panel-body">
				<div class="modal-wrapper">
					<span class="textpesan"></span>
					<div class="form-group">
						<label class='control-label'></label>
						<select name="selected_ptgs" id="selected_ptgs" class="form-control">
							<?php 
							$sql->get_all('op_pegawai',array('status'=>2),array('idp','nip','nm_lengkap'));
							echo '<option value="">- Pilih Petugas -</option>';
							foreach($sql->result as $ptgs){
								echo '<option value="'.$ptgs['idp'].'">'.$ptgs['nip'].' - '.$ptgs['nm_lengkap'].'</option>';
							}
							?>
						
				<footer class="panel-footer">
						</select>
					</div>
				</div>
			
			
				<div class="row">
					<div class="col-md-12 text-right">
						<button class="btn btn-primary modal-confirm">Ganti</button>
						<button class="btn btn-default modal-dismiss">Cancel</button>
					</div>
				</div>
			</footer>
			</div>
			
		</section>
	</form>
</div>
<?php
}
include(AdminFooter);
?>