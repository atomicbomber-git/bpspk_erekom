<?php

include ("../../engine/render.php");

$idbap=base64_decode($_GET['bap']);
if(!ctype_digit($idbap)){
	exit();
}

if($_GET['token']!=md5($idbap.U_ID.'dwsurat_bap')){
	exit();
}

$location= c_BASE_UTAMA."admin/berkas/img/";
$berkas_admin_foto =c_BASE_UTAMA."admin/berkas/dok_sample/";
$berkas_pemohon= c_BASE_UTAMA."/pengajuan/berkas/";

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
$idpengajuan=$row['ref_idp'];

$qq=$sql->run("SELECT DISTINCT(kel.nama_kel) kel FROM tb_hsl_periksa thp 
JOIN ref_data_ikan i ON (i.id_ikan=thp.ref_idikan) 
JOIN ref_kel_ikan kel ON(i.ref_idkel=kel.id_ref)
WHERE thp.ref_idp='".$idpengajuan."' ");
$barang=array();
foreach($qq->fetchAll() as $brg){
	$barang[]=$brg['kel'];
}
$list_brg=implode(' dan ', $barang);

//load info pemohon
$sql->get_row('tb_permohonan',array('idp'=>$row['ref_idp']),'ref_iduser');
$p=$sql->result;
$idpemohon=$p['ref_iduser'];
$u=$sql->run("SELECT u.nama_lengkap,b.gudang_1,
	(SELECT nama_file FROM tb_berkas WHERE jenis_berkas='1' AND ref_iduser='".$idpemohon."' ORDER BY revisi DESC, date_upload DESC LIMIT 1) nama_file 
	FROM tb_userpublic u 
	JOIN tb_biodata b ON(u.iduser=b.ref_iduser)
	WHERE u.iduser='$idpemohon' LIMIT 1");
$pemohon=$u->fetch();

//load data petugas pemeriksa
$pt=$sql->run("SELECT p.nm_lengkap,p.nip,p.jabatan,p.ttd FROM tb_petugas_lap pl LEFT JOIN op_pegawai p ON(pl.ref_idpeg=p.idp) WHERE pl.ref_idp='".$row['ref_idp']."' AND p.status='2'");


$html = '
<html>
<head>
<style>

.table { border-collapse: collapse !important;}
.table td, .table th {background-color: #fff !important;}
.table-bordered th, .table-bordered td {border: 1px solid #000 !important;}
.table-hasil td { padding: 0.2em; }
h1{font-size: 34px;}
h2{font-size: 28px;}
h3{font-size: 22px;}
h4{font-size: 16px;}
h5{font-size: 12px;}
h6{font-size: 10px;}
p {margin: 0 0 10px; line-height: 120%;}
.barcode {
	padding: 1.5mm;
	margin: 0;
	vertical-align: top;
	color: #000000;
}
.barcodecell {
	text-align: right;
	vertical-align: middle;
	padding: 0;
}
</style>
</head>
<body>';

	$html.='<table style="width:100%">
		<tr>
			<td><img  style="vertical-align: top" src="'.$location.'logo-kkp-kop.png" width="100"></td>
			<td style="text-align: center;"><h4><strong>KEMENTERIAN KELAUTAN DAN PERIKANAN</strong></h4>
			<h5>DIREKTORAT JENDERAL PENGELOLAAN RUANG LAUT<h5>
			<h4><strong>LOKA PENGELOLAAN SUMBER DAYA PESISIR DAN LAUT<br/>
			SERANG</strong></h4>
			<small> JALAN RAYA CARITA KM 4.5, DESA CARINGIN KEC. LABUAN KAB. PANDEGLANG PROV. BANTEN </small> <br/>
						TELEPON (0253) 802626, FAKSIMILI (0253) 802616</td>
		</tr>
		<tr><td colspan="2"><hr style="margin:0;border:#000"></td></tr>
	</table>
	<br/>';

	$html.='<table style="width:100%">
	<tr>
		<td style="text-align: center;">
			<h4><strong><u>BERITA ACARA PEMERIKSAAN BARANG MASUK</u></strong></h4>
			Nomor : '.$row['no_surat'].'
		</td>
	</tr>
	<tr>
		<td style="text-align:justify">
			<p><br/><br/>Pada Hari Ini <strong>'.ucwords($hari).'</strong> tanggal <strong>'.ucwords(terbilang($tgl)).'</strong> Bulan <strong>'.ucwords($bln).'</strong> Tahun <strong>'.ucwords(terbilang($thn)).' ('.$thn.')</strong> bertempat di <strong>'.$row['lokasi'].'</strong>, kami yang bertanda tangan dibawah ini :</p> 
		</td>
	</tr></table>';
	$html.='<table style="width:100%">
	<tr>
		<td style="width:5%">1.</td>
		<td style="width:20%">Nama</td>
		<td>: '.$row['nmp1'].'</td>
	</tr>
	<tr>
		<td></td>
		<td>NIP</td>
		<td>: '.$row['nip1'].'</td>
	</tr>
	<tr>
		<td></td>
		<td>Jabatan</td>
		<td>: '.$row['jbtn1'].'</td>
	</tr>
	<tr>
		<td>2.</td>
		<td>Nama</td>
		<td>: '.$row['nmp2'].'</td>
	</tr>
	<tr>
		<td></td>
		<td>NIP</td>
		<td>: '.$row['nip2'].'</td>
	</tr>
	<tr>
		<td></td>
		<td>Jabatan</td>
		<td>: '.$row['jbtn2'].'</td>
	</tr></table>';
	$html.='<table style="width:100%">
	<tr>
		<td colspan="3" style="text-align:justify">
			<br/>
			<p>Menerangkan Bahwa telah melakukan pemeriksaan barang masuk berupa sampel '.$list_brg.' milik:</p>
		</td>
	</tr>
	<tr>
		<td style="width:5%"></td>
		<td style="width:20%">Nama</td>
		<td>: '.$pemohon['nama_lengkap'].'</td>
	</tr>
	<tr>
		<td></td>
		<td>Alamat</td>
		<td>: '.$pemohon['gudang_1'].'</td>
	</tr>
	<tr>
		<td colspan="3" style="text-align:justify">
			<p>'.$row['redaksi'].'</p>
			<br/>
			<p>Demikian Berita Acara Pemeriksaan ini dibuat dengan sebenar-benarnya, untuk dapat dipergunakan sebagaimana mestinya.</p>
		</td>
	</tr>
</table>';

$html.='<table width="100%">
	<tr>
		<td colspan="2" style="text-align: center;">
			<br>
			'.tanggalIndo($row['tgl_surat'],'j F Y').',<br>
			Tim Pemeriksa
		</td>
	</tr>
	<tr>
		<td width="50%" style="text-align: center;">
			<br>
			<img height="85px" src="'.$location.$row['ttd1'].'">
			<p>'.$row['nmp1'].'</p>
		</td>
		<td width="50%" style="text-align: center;">
			<br>
			<img height="85px" src="'.$location.$row['ttd2'].'">
			<p>'.$row['nmp2'].'</p>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: center;">
			<br>Perwakilan Perusahaan/ Pengirim<br>
			<img height="85px" src="'.$berkas_pemohon.$pemohon['nama_file'].'">
			<p>'.$pemohon['nama_lengkap'].'</p>
		</td>
	</tr>
</table><pagebreak />';
//tabel hasil pemeriksaan
$sql->get_row('tb_pemeriksaan',array('ref_idp'=>$idpengajuan),array('tgl_periksa'));
$tgl=$sql->result;
$html.='<table class="table">
<tr>
	<td width="35%">Tanggal Pemeriksaan</td>
	<td>: '.tanggalIndo($tgl['tgl_periksa'],'j F Y').'</td>
</tr>';

$pt=$sql->run("SELECT op.nm_lengkap, op.nip FROM tb_petugas_lap pl JOIN op_pegawai op ON (pl.ref_idpeg=op.idp) WHERE pl.ref_idp='$idpengajuan'");
if($pt->rowCount()>0){
	$no=0;
	foreach($pt->fetchAll() as $ptgs){
		$no++;
		$html.='<tr>
			<td width="35%">Petugas Pemeriksa '.$no.'</td>
			<td>: '.$ptgs['nm_lengkap'].' ('.$ptgs['nip'].')</td>
		</tr>';
	}
}
$html.='</table><br/>';
$html.='<table style="width:100%" class="table table-bordered table-hasil">
	<caption><h4>Tabel Hasil Pemeriksaan Sampel</h4></caption>
	<tr>
		<td rowspan="2">No</td>
		<td rowspan="2">Jenis Produk</td>
		<td rowspan="2">Jenis Ikan</td>
		<td colspan="3">Sampel (Terkecil/Terbesar)</td>
		<td rowspan="2">Berat Total<br>(Kg)</td>
		<td rowspan="2">Jlh Kemasan </td>
		<td rowspan="2">Keterangan</td>
	</tr>
	<tr>
		<td>Panjang<br>(Cm)</td>
		<td>Lebar<br>(Cm)</td>
		<td>Berat<br>(Kg)</td>
	</tr>
	';

$t=$sql->run("SELECT thp.*, rdi.nama_ikan, rdi.nama_latin,rjs.jenis_sampel FROM tb_hsl_periksa thp 
	JOIN ref_data_ikan rdi ON(rdi.id_ikan=thp.ref_idikan)
	JOIN ref_jns_sampel rjs ON(rjs.id_ref=thp.ref_jns_sampel) WHERE thp.ref_idp='".$idpengajuan."' ");
if($t->rowCount()>0){
	$not=0;
	foreach($t->fetchAll() as $rp){
		$not++;
		$html.='
		<tr>
			<td>'.$not.'</td>
			<td>'.$rp['jenis_sampel'].'</td>
			<td>'.$rp['nama_ikan']." (<em>".$rp['nama_latin']."</em>)".'</td>
			<td>'.$rp['pjg'].''.(($rp['pjg2']!='0.00')?" / ".$rp['pjg2']:"").'</td>
			<td>'.$rp['lbr'].''.(($rp['lbr2']!='0.00')?" / ".$rp['lbr2']:"").'</td>
			<td>'.$rp['berat'].''.(($rp['berat2']!='0.00')?" / ".$rp['berat2']:"").'</td>
			<td>'.$rp['tot_berat'].'</td>
			<td>'.$rp['kuantitas'].'</td>
			<td>'.$rp['ket'].'</td>
		</tr>';
	}
}
$html.='</table><pagebreak />';

//dokumentasi pemeriksaan
$html.='<table class="table">';
$html.='<caption><h4>Dokumentasi Pemeriksaan Sampel</h4></caption>';
$sql->get_all('tb_dokumentasi',array('ref_idp'=>$idpengajuan),array('nm_file','ket_foto','id_dok'));
if($sql->num_rows>0){
	$nog=0;
	$html.='<tr>';
	foreach($sql->result as $gbr){
		$nog++;
		$html.='<td><img style="padding:10px" width="100%" src="'.$berkas_admin_foto.$gbr['nm_file'].'"><p><h3>'.$gbr['ket_foto'].'</h3></p></td>';
		if($nog>1){
			if($nog%2==0){
				$html.='</tr><tr>';
			}
		}
	}
	$html.='</tr>';
}
$html.='</table>';

$html.='</body>
</html>';

// echo $html;
include("../../../assets/mpdf60/mpdf.php");

$mpdf=new mPDF('','','10','Arial',15,10,15,10,10,10);
$mpdf->WriteHTML($html);
$mpdf->Output("bap-".$idbap.".pdf",'I'); 


exit;
?>