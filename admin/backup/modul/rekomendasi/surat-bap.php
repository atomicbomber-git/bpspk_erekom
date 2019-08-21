<?php
require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('nav li.nav2').addClass('nav-active');
});
</script>
<script src=\"custom-2.js\"></script>
";

$idbap=base64_decode($_GET['bap']);
if(!ctype_digit($idbap)){
	exit();
}

if($_GET['token']!=md5($idbap.U_ID.'surat_bap')){
	exit();
}

//load hasil data BAP
$bap=$sql->run("SELECT bap.*,
	p1.nm_lengkap nmp1, p1.nip nip1, p1.jabatan jbtn1, p1.ttd ttd1, 
	p2.nm_lengkap nmp2, p2.nip nip2, p2.jabatan jbtn2, p2.ttd ttd2 FROM tb_bap bap 
	LEFT JOIN op_pegawai p1 ON(p1.idp=bap.ptgs1)
	LEFT JOIN op_pegawai p2 ON(p2.idp=bap.ptgs2)
	WHERE bap.id_bap='$idbap' LIMIT 1");
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

// echo $sql->sql;
//load data petugas pemeriksa
$pt=$sql->run("SELECT p.nm_lengkap,p.nip,p.jabatan,p.ttd FROM tb_petugas_lap pl LEFT JOIN op_pegawai p ON(pl.ref_idpeg=p.idp) WHERE pl.ref_idp='".$row['ref_idp']."' AND p.status='2'");

?>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Berita Acara Pemeriksaan</h2>
	
		<div class="right-wrapper pull-right">
			<ol class="breadcrumbs">
				<li>
					<a href="<?php echo c_MODULE;?>">
						<i class="fa fa-home"></i>
					</a>
				</li>
				<li><a href="./pemeriksaan-sample.php"><span>Pemeriksaan Sampel</span></a></li>
				<li><span>Berita Acara Pemeriksaan</span></li>
			</ol>
			<a class="sidebar-right-toggle" ><i class="fa fa-chevron-left"></i></a>
		</div>
	</header>
	<div class="row">
		<div class="col-md-12">
			<section class="panel">
				<div class="panel-body">
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
				<footer class="panel-footer">
					<div class="form-group">
						<a target="_blank" href="download-bap.php?bap=<?php echo base64_encode($idbap);?>&token=<?php echo md5($idbap.U_ID.'dwsurat_bap');?>" class="btn btn-sm btn-primary">Download PDF</a>
					</div>
				</footer>
			</section>
		</div>
	</div>
</section>
<?php
include(AdminFooter);
?>
