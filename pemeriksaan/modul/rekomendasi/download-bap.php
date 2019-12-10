<?php

use App\Models\HasilPeriksa;
use App\Models\Permohonan;
use App\Services\Contracts\Template;
use Jenssegers\Date\Date;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;

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


$template = container(Template::class);

$permohonan = Permohonan::find($idpengajuan)
	->load([
		"pemeriksaan",
		"petugas.pegawai",
		"hasil_periksa",
		"hasil_periksa.jenis_sampel",
		"hasil_periksa.data_ikan",
		"hasil_periksa.satuan_barang",
	]);

$html .= $template->render("letter/bap_hasil_pemeriksaan_table", [
	"permohonan" => $permohonan
]);

$html.='<table>';
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


$html .= "</html>";


$mpdf = container(Mpdf::class);
$mpdf->WriteHTML(file_get_contents(container("mpdf_css_path")), HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);
$mpdf->Output("rekomendasi-{$row['kode_surat']}.pdf", 'I');
