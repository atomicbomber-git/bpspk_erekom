<?php
include_once ("engine/render.php");

$ITEM_HEAD = "bootstrap.css, font-awesome.css, magnific-popup.css, datepicker3.css, pnotify.custom.css, select2.css, codemirror.css, monokai.css, bootstrap-tagsinput.css, bootstrap-timepicker.css, theme.css, default.css, datatables.css, modernizr.js";

$ITEM_FOOT = "jquery.js, jquery.browser.mobile.js, bootstrap.js, nanoscroller.js, bootstrap-datepicker.js, magnific-popup.js, jquery.placeholde,jquery.dataTables.js,datatables.js, pnotify.custom.js, jquery.appear.js, select2.js, jquery.autosize.js, bootstrap-tagsinput.js, bootstrap-timepicker.js, theme.js, theme.init.js,jquery.validate.js,jquery.validate.msg.id.js ";

require_once(c_THEMES."auth.php");

$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.landing').addClass('nav-active');
});
</script>
<script src=\"custom-3.js\"></script>
";

$token=$_GET['token'];
$kdsurat=$_GET['surat'];

if($token=="" OR $kdsurat==""){
	//exit();
}

if(!ctype_digit($kdsurat)){
	//exit();
}

if($token!=(md5('view'.$kdsurat.'admin'))){
	//exit();
}

$sql->get_row('tb_rekomendasi',array('kode_surat'=>$kdsurat),array('ref_idp'));
$ridp=$sql->result;
$idpengajuan=$ridp['ref_idp'];

$sql->get_row('tb_permohonan',array('idp'=>$idpengajuan,'status'=>5),'*');
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
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Pengesahan Permohonan Rekomendasi</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><span>Pengesahan Permohonan Rekomendasi</span></li>
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
							<td>Alamat Gudang</td>
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
				</div>
			</section>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<section class="panel panel-featured panel-featured-primary">
				<header class="panel-heading">
					<div class="panel-actions"><a href="#" class="fa fa-caret-down"></a></div>
					<h2 class="panel-title">Hasil Pemeriksaan</h2>
				</header>
				<div class="panel-body">
					<table class="table">
						<?php
						$sql->get_row('tb_pemeriksaan',array('ref_idp'=>$idpengajuan),array('tgl_periksa'));
						$tgl=$sql->result;
						?>
						<tr>
							<td style="width:20%">Tanggal Pemeriksaan</td>
							<td>: <?php echo tanggalIndo($tgl['tgl_periksa'],'j F Y');?></td>
						</tr>
						<?php
						$pt=$sql->run("SELECT op.nm_lengkap, op.nip FROM tb_petugas_lap pl JOIN op_pegawai op ON (pl.ref_idpeg=op.idp) WHERE pl.ref_idp='$idpengajuan'");
						if($pt->rowCount()>0){
							$no=0;
							foreach($pt->fetchAll() as $ptgs){
								$no++;
								echo '<tr>
									<td style="width:20%">Petugas Pemeriksa '.$no.'</td>
									<td>: '.$ptgs['nm_lengkap'].' ('.$ptgs['nip'].')</td>
								</tr>';
							}
						}
						?>
						
					</table>
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>No</th>
								<th>Jenis Produk</th>
								<th>Jenis Ikan</th>
								<th>Panjang<br>Sampel(Cm)</th>
								<th>Lebar<br>Sampel(Cm)</th>
								<th>Berat<br>Sampel(Kg)</th>
								<th>Berat Total(Kg)</th>
								<th>Jlh Kemasan </th>
								<th>Keterangan</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$t=$sql->run("SELECT thp.*, rdi.nama_ikan, rdi.nama_latin,rjs.jenis_sampel FROM tb_hsl_periksa thp 
								JOIN ref_data_ikan rdi ON(rdi.id_ikan=thp.ref_idikan)
								JOIN ref_jns_sampel rjs ON(rjs.id_ref=thp.ref_jns_sampel) WHERE thp.ref_idp='".$idpengajuan."'");
							if($t->rowCount()>0){
								$not=0;
								foreach($t->fetchAll() as $rp){
									$not++;
									?>
									<tr>
										<td><?php echo $not;?></td>
										<td><?php echo $rp['jenis_sampel'];?></td>
										<td><?php echo $rp['nama_ikan']." (<em>".$rp['nama_latin']."</em>)";?></td>
										<td><?php echo $rp['pjg'];?></td>
										<td><?php echo $rp['lbr'];?></td>
										<td><?php echo $rp['berat'];?></td>
										<td><?php echo $rp['tot_berat'];?></td>
										<td><?php echo $rp['kuantitas'];?></td>
										<td><?php echo $rp['ket'];?></td>
									</tr>
									<?php
								}
							}
							?>
						</tbody>
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
					<h2 class="panel-title">Foto Dokumentasi Pemeriksaan</h2>
				</header>
				<div class="panel-body">
					<table class="table">
					<?php
					$sql->get_all('tb_dokumentasi',array('ref_idp'=>$idpengajuan),array('nm_file','ket_foto','id_dok'));
					if($sql->num_rows>0){
						$nog=0;
						echo '<tr>';
						foreach($sql->result as $gbr){
							$nog++;
							echo '<td><img width="100%" src="'.ADM_FOTO.$gbr['nm_file'].'" href="'.ADM_FOTO.$gbr['nm_file'].'" class="img-prev"><p>'.$gbr['ket_foto'].'</p></td>';
							if($nog>1){
								if($nog%2!=0){
									echo '</tr><tr>';
								}
							}
						}
						echo '</tr>';
					}
					?>
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
					<h2 class="panel-title">Berita Acara Pemeriksaan</h2>
				</header>
				<div class="panel-body">
					<?php
					//load hasil data BAP
					$bap=$sql->run("SELECT bap.*,
						p1.nm_lengkap nmp1, p1.nip nip1, p1.jabatan jbtn1, p1.ttd ttd1, 
						p2.nm_lengkap nmp2, p2.nip nip2, p2.jabatan jbtn2, p2.ttd ttd2 FROM tb_bap bap 
						LEFT JOIN op_pegawai p1 ON(p1.idp=bap.ptgs1)
						LEFT JOIN op_pegawai p2 ON(p2.idp=bap.ptgs2)
						WHERE bap.ref_idp='$idpengajuan' LIMIT 1");
					$row=$bap->fetch();
					$hari=tanggalIndo($row['tgl_surat'],'l');
					$tgl=tanggalIndo($row['tgl_surat'],'j');
					$bln=tanggalIndo($row['tgl_surat'],'F');
					$thn=tanggalIndo($row['tgl_surat'],'Y');

					//load list pengajuan
					$sql->get_all('tb_barang',array('ref_idphn'=>$row['ref_idp']),'nm_barang');
					$barang=array();
					foreach($sql->result as $brg){
						$barang[]=$brg['nm_barang'];
					}
					$list_brg=implode(', ', $barang);

					//load info pemohon
					$sql->get_row('tb_permohonan',array('idp'=>$row['ref_idp']),'ref_iduser');
					$p=$sql->result;
					$idpemohon=$p['ref_iduser'];
					$u=$sql->run("SELECT u.nama_lengkap,b.alamat,
						(SELECT nama_file FROM tb_berkas WHERE jenis_berkas='1' AND ref_iduser='".$idpemohon."' ORDER BY revisi DESC, date_upload DESC LIMIT 1) nama_file 
						FROM tb_userpublic u 
						JOIN tb_biodata b ON(u.iduser=b.ref_iduser)
						WHERE u.iduser='$idpemohon' LIMIT 1");
					$pemohon=$u->fetch();

					//load data petugas pemeriksa
					$pt=$sql->run("SELECT p.nm_lengkap,p.nip,p.jabatan,p.ttd FROM tb_petugas_lap pl LEFT JOIN op_pegawai p ON(pl.ref_idpeg=p.idp) WHERE pl.ref_idp='".$row['ref_idp']."' AND p.status='2'");
					?>
					<table style="width:100%">
						<tr style="border-bottom:2pt solid black;">
							<td><img src="<?php echo ADM_IMAGES;?>logo-kkp-kop.png" width="150"></td>
							<td style="text-align: center;"><h4><strong>KEMENTERIAN KELAUTAN DAN PERIKANAN</strong></h4>
							<h5>DIREKTORAT JENDERAL PENGELOLAAN RUANG LAUT<h5>
							<h4><strong>BALAI PENGELOLAAN SUMBER DAYA PESISIR DAN LAUT<br/>
							PONTIANAK</strong></h4>
							<small>JALAN HUSEIN HAMZAH NOMOR 01 PAALLIMA, PONTIANAK 78114 TELP.(0561)766691,
							FAX(0561)766465, <br>WEBSITE:bpsplpontianak.kkp.go.id, EMAIL :bpsplpontianak@gmail.com</small></td>
						</tr>
					</table>
					<table style="width:100%">
						<tr>
							<td colspan="3" style="text-align: center;">
								<h5><strong><u>BERITA ACARA PEMERIKSAAN BARANG MASUK</u></strong></h5>
								Nomor : <?php echo $row['no_surat'];?>
								<br/><br/>
							</td>
						</tr>
						<tr>
							<td colspan="3">
								<p>Pada Hari Ini <strong><?php echo ucwords($hari);?></strong> tanggal <strong><?php echo ucwords(terbilang($tgl));?></strong> Bulan <strong><?php echo ucwords($bln);?></strong> Tahun <strong><?php echo ucwords(terbilang($thn));?> (<?php echo $thn;?>)</strong> bertempat di <strong><?php echo $row['lokasi'];?></strong>, kami yang bertanda tangan dibawah ini :</p> 
							</td>
						</tr>
						<tr>
							<td>1.</td>
							<td>Nama</td>
							<td>: <?php echo $row['nmp1'];?></td>
						</tr>
						<tr>
							<td></td>
							<td>NIP</td>
							<td>: <?php echo $row['nip1'];?></td>
						</tr>
						<tr>
							<td></td>
							<td>Jabatan</td>
							<td>: <?php echo $row['jbtn1'];?></td>
						</tr>
						<tr>
							<td>2.</td>
							<td>Nama</td>
							<td>: <?php echo $row['nmp2'];?></td>
						</tr>
						<tr>
							<td></td>
							<td>NIP</td>
							<td>: <?php echo $row['nip2'];?></td>
						</tr>
						<tr>
							<td></td>
							<td>Jabatan</td>
							<td>: <?php echo $row['jbtn2'];?></td>
						</tr>
						<tr>
							<td colspan="3">
								<p>Menerangkan Bahwa telah melakukan pemeriksaan barang masuk berupa sampel <?php echo $list_brg;?> milik:</p>
							</td>
						</tr>
						<tr>
							<td colspan="2" width="20%">Nama</td>
							<td>: <?php echo $pemohon['nama_lengkap'];?></td>
						</tr>
						<tr>
							<td colspan="2" width="20%">Alamat</td>
							<td>: <?php echo $pemohon['alamat'];?></td>
						</tr>
						<tr>
							<td colspan="3">
								<br/>
								<p><?php echo $row['redaksi'];?></p>
								<p>Demikian Berita Acara Pemeriksaan ini dibuat dengan sebenar-benarnya, untuk dapat dipergunakan sebagaimana mestinya.</p>
							</td>
						</tr>
					</table>
					<table width="100%">
						<tr>
							<td colspan="2" style="text-align: center;">
								<br>
								<?php echo tanggalIndo($row['tgl_surat'],'j F Y');?>,<br>
								Tim Pemeriksa
							</td>
						</tr>
						<tr>
							<td width="50%" style="text-align: center;">
								<br>
								<a href="#"><img height="100px" src="<?php echo ADM_IMAGES.$row['ttd1'];?>"></a>
								<p><?php echo $row['nmp1'];?></p>
							</td>
							<td width="50%" style="text-align: center;">
								<br>
								<a href="#"><img height="100px" src="<?php echo ADM_IMAGES.$row['ttd2'];?>"></a>
								<p><?php echo $row['nmp2'];?></p>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: center;">
								<br>
								Perwakilan Perusahaan/ Pengirim
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: center;">
								<br>
								<a href="#"><img height="100px" src="<?php echo BERKAS.$pemohon['nama_file'];?>"></a>
								<p><?php echo $pemohon['nama_lengkap'];?></p>
							</td>
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
					<h2 class="panel-title">Draft Surat Rekomendasi</h2>
				</header>
				<div class="panel-body">
					<?php
					//load data surat rekomendasi
					$rek=$sql->run("SELECT tr.*, tp.tgl_pengajuan, tp.tujuan, tp.jenis_angkutan, tu.nama_lengkap, tb.no_surat nobap, tb.tgl_surat tglbap, op.nm_lengkap penandatgn, op.jabatan, op.ttd,ou.lvl 
					FROM tb_rekomendasi tr
					JOIN tb_permohonan tp ON (tr.ref_idp=tp.idp)
					JOIN tb_userpublic tu ON (tu.iduser=tr.ref_iduser)
					JOIN tb_bap tb ON (tp.idp=tb.ref_idp)
					JOIN op_pegawai op ON(tr.pnttd=op.nip)
					JOIN op_user ou ON(ou.ref_idpeg=op.idp)
					WHERE tr.ref_idp='".$idpengajuan."' LIMIT 1");

					$row=$rek->fetch();
					?>
					<table style="width:100%">
						<tr style="border-bottom:2pt solid black;">
							<td><img src="<?php echo ADM_IMAGES;?>logo-kkp-kop.png" width="150"></td>
							<td style="text-align: center;"><h4><strong>KEMENTERIAN KELAUTAN DAN PERIKANAN</strong></h4>
							<h5>DIREKTORAT JENDERAL PENGELOLAAN RUANG LAUT<h5>
							<h4><strong>BALAI PENGELOLAAN SUMBER DAYA PESISIR DAN LAUT<br/>
							PONTIANAK</strong></h4>
							<small>JALAN HUSEIN HAMZAH NOMOR 01 PAALLIMA, PONTIANAK 78114 TELP.(0561)766691,
							FAX(0561)766465, <br>WEBSITE:bpsplpontianak.kkp.go.id, EMAIL :bpsplpontianak@gmail.com</small></td>
						</tr>
					</table>
					<br/>
					<table style="width:100%">
						<tr>
							<td>Nomor</td>
							<td>: <?php echo $row['no_surat'];?></td>
							<td style="text-align:right"><?php echo tanggalIndo($row['tgl_surat'],'j F Y');?></td>
						</tr>
						<tr>
							<td>Perihal</td>
							<td>: <?php echo $row['perihal'];?></td>
							<td></td>
						</tr>
					</table>
					<table style="width:100%">
						<tr>
							<td>
							<br>Kepada
							<br>Yth. <?php echo $row['nama_lengkap'];?>
							<br>di -
							<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tempat</td>
							<td style="text-align:right"></td>
						</tr>
					</table>
					<table style="width:100%">
						<tr>
							<td><br>
							<p>Menindaklanjuti Surat Saudara tanggal <?php echo tanggalIndo($row['tgl_pengajuan'],'j F Y');?> perihal permohonan rekomendasi untuk lalu lintas hiu/pari ke <?php echo $row['tujuan'];?> melalui jalur <?php echo ucwords($row['jenis_angkutan']);?>, dengan ini disampaikan bahwa Petugas Balai Pengelolaan Sumberdaya Pesisir dan Laut Pontianak telah melakukan identifikasi yang tertuang dalam Berita Acara Nomor : <?php echo $row['nobap'];?> tanggal <?php echo tanggalIndo($row['tglbap'],'j F Y');?> dengan hasil:</p>
							</td>
						</tr>
					</table>
					<table style="width:100%" class="table table-bordered" >
						<tr>
							<td width="5%">No</td>
							<td>Jenis Produk</td>
							<td width="12%">Kemasan<br>(Colly)</td>
							<td width="12%">No.Segel</td>
							<td width="12%">Berat(Kg)</td>
							<td>Keterangan</td>
						</tr>
						<?php
						$dt=$sql->run("SELECT thp.*, rjs.jenis_sampel FROM tb_rek_hsl_periksa thp JOIN ref_jns_sampel rjs ON (rjs.id_ref=thp.ref_jns) WHERE thp.ref_idrek='".$row['idrek']."' ORDER BY thp.ref_jns ASC");
						if($dt->rowCount()>0){
							$no=0;
							foreach($dt->fetchAll() as $dtrow){
								$no++;
								?>
								<tr>
									<td width="5%"><?php echo $no;?></td>
									<td><?php echo  $dtrow['jenis_sampel'];?></td>
									<td><?php echo $dtrow['kemasan'];?></td>
									<td><?php echo $dtrow['no_segel'];?></td>
									<td><?php echo $dtrow['berat'];?></td>
									<td><?php echo $dtrow['keterangan'];?></td>
								</tr>
								<?php
							}
						}
						?>
					</table>
					<table style="width:100%">
						<tr>
							<td><br><p><?php echo $row['redaksi'];?></p></td>
						</tr>
						<tr>
							<td><p>Demikian disampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih.</p></td>
						</tr>
					</table>
					<table style="width:100%">
						<tr>
							<td width="60%"></td>
							<td width="60%" style="text-align:center">
								<?php echo (($row['lvl']==90)?"Kepala Balai":"Plh. Kepala Balai");?>
								<p><a href="#"><img height="100px" src="<?php echo ADM_IMAGES.$row['ttd'];?>"></a></p>
								<?php echo $row['penandatgn'];?>
							</td>
						</tr>
						<tr>
							<td colspan="2">
							Tembusan:
							<ol>
								<li>Direktur Jenderal PRL</li>
								<li>Direktur Konservasi Keanekaragaman dan Hayati Laut</li>
								<li>Kepala Stasiun KIPM Kelas 1 Pontianak</li>
							</ol>
							</td>
						</tr>
					</table>
				</div>
				<footer class="panel-footer">
					<a href="<?php echo c_DOMAIN_UTAMA."download.php?surat=".$kdsurat."&token=".md5('download'.$kdsurat.'public');?>" target="_blank" class="btn btn-sm"> Download PDF</a>
				</footer>
			</section>
		</div>
	</div>
</section>
<?php
}
include(AdminFooter);
?>
