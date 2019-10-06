<?php
use App\Services\Letter;

require_once("config.php");
$SCRIPT_FOOT = "
<script>
$(document).ready(function(){
	$('ul li.nav-ba').addClass('active');
});
</script>
<script src=\"bap.js\"></script>
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

$qq=$sql->run("SELECT DISTINCT(kel.nama_kel) kel FROM tb_hsl_periksa thp 
JOIN ref_data_ikan i ON (i.id_ikan=thp.ref_idikan) 
JOIN ref_kel_ikan kel ON(i.ref_idkel=kel.id_ref)
WHERE thp.ref_idp='".$row['ref_idp']."' ");
$barang=array();
foreach($qq->fetchAll() as $brg){
	$barang[]=$brg['kel'];
}
$list_brg=implode(' dan ', $barang);

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
<body class="hold-transition skin-blue sidebar-mini">

<div class="content-wrapper">
	<section class="content-header">
		<h1>
		Berita Acara Pemeriksaan
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo c_URL;?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="./input-bap.php">Berita Acara Pemeriksaan</a></li>
			<li class="active">Preview Berita Acara Pemeriksaan</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<section class="panel">
					<div class="panel-body">

					<?= container(Letter::class)->getHeaderContentHTML(ADM_IMAGES) ?>
					<br/>

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
</div>

</div>
</body>
<?php
include(AdminFooter);
?>
