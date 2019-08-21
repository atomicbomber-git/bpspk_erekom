<?php
include ("pemeriksaan/engine/render.php");

$token=$_GET['token'];
$kdsurat=$_GET['surat'];
if($token=="" OR $kdsurat==""){
	exit();
}

if(!ctype_digit($kdsurat)){
	exit();
}

if($token!=(md5('download'.$kdsurat.'public'))){
	exit();
}

$berkas_rek='rek_files/rekomendasi-'.$kdsurat.'.pdf';
if(file_exists($berkas_rek)){
	header("Content-disposition: attachment; filename=rekomendasi-".$kdsurat.".pdf");
	header("Content-type: application/pdf");
	readfile($berkas_rek);
	exit();
}

$berkas_admin		= c_BASE_UTAMA."admin/berkas/img/";
$berkas_admin_foto	= c_BASE_UTAMA."admin/berkas/dok_sample/";
$berkas_pemohon		= c_BASE_UTAMA."pengajuan/berkas/";
$berkas_admin_url	= c_DOMAIN_UTAMA."admin/berkas/img/";

$rek=$sql->run("SELECT tr.*, tp.tgl_pengajuan, tp.tujuan, tp.jenis_angkutan, tu.nama_lengkap, tb.no_surat nobap, tb.tgl_surat tglbap, op.nm_lengkap penandatgn, op.jabatan, op.ttd,ou.lvl 
FROM tb_rekomendasi tr
JOIN tb_permohonan tp ON (tr.ref_idp=tp.idp)
JOIN tb_userpublic tu ON (tu.iduser=tr.ref_iduser)
JOIN tb_bap tb ON (tp.idp=tb.ref_idp)
JOIN op_pegawai op ON(tr.pnttd=op.nip)
JOIN op_user ou ON(ou.ref_idpeg=op.idp)
WHERE tr.kode_surat='".$kdsurat."' LIMIT 1");
$html = '
<html>
<head>
<style>

.table { border-collapse: collapse !important;}
.table td, .table th {background-color: #fff !important;}
.table-bordered th, .table-bordered td {border: 1px solid #000 !important;}
.table-hasil td { padding: 0.4em; }
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
<body>
';
if(file_exists(c_BASE_UTAMA.'assets/images/img_qrcode/'.$kdsurat.'_qr.png')){
	$qrcode='<img  style="vertical-align: top" src="'.c_BASE_UTAMA.'assets/images/img_qrcode/'.$kdsurat.'_qr.png" width="100">';
}else{
	$qrcode="";
}

if($rek->rowCount()>0){
	$row=$rek->fetch();

	if($row['tgl_surat']<'2017-06-04'){
		header('location:download_o.php?surat='.$kdsurat.'&token='.$token);
		exit();
	}
	$html.='<table style="width:100%">
		<tr>
			<td><img  style="vertical-align: top" src="'.$berkas_admin.'logo-kkp-kop.png" width="100"></td>
			<td style="text-align: center;"><h4><strong>KEMENTERIAN KELAUTAN DAN PERIKANAN</strong></h4>
			<h5>DIREKTORAT JENDERAL PENGELOLAAN RUANG LAUT<h5>
			<h4><strong>BALAI PENGELOLAAN SUMBER DAYA PESISIR DAN LAUT<br/>
			PONTIANAK</strong></h4>
			<small>JALAN HUSEIN HAMZAH NOMOR 01 PAL LIMA, PONTIANAK 78114 TELP.(0561)766691,
			FAX(0561)766465, WEBSITE:bpsplpontianak.kkp.go.id, EMAIL :bpsplpontianak@gmail.com</small></td>
		</tr>
		<tr><td colspan="2"><hr style="margin:0;border:#000"></td></tr>
	</table>
	<br/>';
	$html.='<table class="table" style="width:100%">
		<tr>
			<td>Nomor</td>
			<td>: '.$row['no_surat'].'</td>
			<td style="text-align:right">'.tanggalIndo($row['tgl_surat'],'j F Y').'</td>
		</tr>
		<tr>
			<td>Perihal</td>
			<td>: '.$row['perihal'].'</td>
			<td></td>
		</tr>
	</table>';
	$html.='<table style="width:100%">
		<tr>
			<td>
			<br>Kepada
			<br>Yth. '.$row['nama_lengkap'].'
			<br>di -
			<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tempat</td>
			<td class="barcodecell">'.$qrcode.'
			<p>'.$row['kode_surat'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<p></td>
		</tr>
	</table>';
	$html.='<table style="width:100%">
		<tr>
			<td style="text-align:justify;"><br>
			<p>Menindaklanjuti Surat Saudara tanggal '.tanggalIndo($row['tgl_pengajuan'],'j F Y').' perihal permohonan rekomendasi untuk lalu lintas hiu/pari ke '.$row['tujuan'].' melalui jalur '.ucwords($row['jenis_angkutan']).', dengan ini disampaikan bahwa Petugas Balai Pengelolaan Sumberdaya Pesisir dan Laut Pontianak telah melakukan identifikasi yang tertuang dalam Berita Acara Nomor : '.$row['nobap'].' tanggal '.tanggalIndo($row['tglbap'],'j F Y').' dengan hasil:</p>
			</td>
		</tr>
	</table>';
	$html.='<table style="width:100%" class="table table-bordered table-hasil" >
		<tr>
			<td width="5%">No</td>
			<td>Jenis Ikan</td>
			<td width="12%">Kemasan</td>
			<td width="12%">No.Segel</td>
			<td width="12%">Berat Ikan(Kg)</td>
			<td>Keterangan</td>
		</tr>';
		$dt=$sql->run("SELECT thp.*, rjs.jenis_sampel, rdi.nama_latin FROM tb_rek_hsl_periksa thp JOIN ref_jns_sampel rjs ON (rjs.id_ref=thp.ref_jns) LEFT JOIN ref_data_ikan rdi ON(rdi.id_ikan=thp.ref_idikan) WHERE thp.ref_idrek='".$row['idrek']."' ORDER BY thp.ref_jns ASC");
		if($dt->rowCount()>0){
			$no=0;
			foreach($dt->fetchAll() as $dtrow){
				$no++;
				$html.='
				<tr>
					<td width="5%">'.$no.'</td>
					<td><em>'.$dtrow['nama_latin'].'</em></td>
					<td>'.$dtrow['kemasan'].' '.$dtrow['satuan'].'</td>
					<td>'.$dtrow['no_segel'].'</td>
					<td>'.(($dtrow['berat']=='0.00')?"":$dtrow['berat']).'</td>
					<td>'.$dtrow['keterangan'].'</td>
				</tr>';
			}
		}
	$html.='</table>';
	$html.='<table style="width:100%">
		<tr>
			<td style="text-align:justify;"><br><p>'.$row['redaksi'].'</p></td>
		</tr>
		<tr>
			<td><p>Demikian disampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih.</p></td>
		</tr>
	</table>';
	$tmb=$sql->run("SELECT rbk.nama FROM tb_rekomendasi tr JOIN ref_balai_karantina rbk ON(rbk.idbk=tr.ref_bk) WHERE tr.ref_idp='".$row['ref_idp']."' LIMIT 1");
	$karantina=$tmb->fetch();
	$html.='<table style="width:100%">
		<tr>
			<td width="60%"></td>
			<td width="40%" style="text-align:center"><br>
				'.(($row['lvl']==90)?"Kepala Balai":"Plh. Kepala Balai").'
				<p><img height="150px" style="z-index:-1;" src="'.$berkas_admin.$row['ttd'].'"></p>
				'.$row['penandatgn'].'
			</td>
		</tr>
		<tr>
			<td colspan="2">
			Tembusan:
			<ol>
				<li>Direktur Jenderal PRL</li>
				<li>Direktur Konservasi Keanekaragaman dan Hayati Laut</li>
				<li>Kepala '.$karantina['nama'].'</li>
			</ol>
			</td>
		</tr>
	</table><pagebreak />';
}

//-------------------------------------------------
//load hasil data BAP
$idpengajuan=$row['ref_idp'];
$bap=$sql->run("SELECT bap.*,
	p1.nm_lengkap nmp1, p1.nip nip1, p1.jabatan jbtn1, p1.ttd ttd1, 
	p2.nm_lengkap nmp2, p2.nip nip2, p2.jabatan jbtn2, p2.ttd ttd2 FROM tb_bap bap 
	LEFT JOIN op_pegawai p1 ON(p1.idp=bap.ptgs1)
	LEFT JOIN op_pegawai p2 ON(p2.idp=bap.ptgs2)
	WHERE bap.ref_idp='$idpengajuan' LIMIT 1");
$rowbap=$bap->fetch();
$hari=tanggalIndo($rowbap['tgl_surat'],'l');
$tgl=tanggalIndo($rowbap['tgl_surat'],'j');
$bln=tanggalIndo($rowbap['tgl_surat'],'F');
$thn=tanggalIndo($rowbap['tgl_surat'],'Y');

$qq=$sql->run("SELECT DISTINCT(kel.nama_kel) kel FROM tb_hsl_periksa thp 
JOIN ref_data_ikan i ON (i.id_ikan=thp.ref_idikan) 
JOIN ref_kel_ikan kel ON(i.ref_idkel=kel.id_ref)
WHERE thp.ref_idp='".$rowbap['ref_idp']."' ");
$barang=array();
foreach($qq->fetchAll() as $brg){
	$barang[]=$brg['kel'];
}
$list_brg=implode(' dan ', $barang);

//load info pemohon
$sql->get_row('tb_permohonan',array('idp'=>$rowbap['ref_idp']),'ref_iduser');
$p=$sql->result;
$idpemohon=$p['ref_iduser'];
$u=$sql->run("SELECT u.nama_lengkap,b.alamat,
	(SELECT nama_file FROM tb_berkas WHERE jenis_berkas='1' AND ref_iduser='".$idpemohon."' ORDER BY revisi DESC, date_upload DESC LIMIT 1) nama_file 
	FROM tb_userpublic u 
	JOIN tb_biodata b ON(u.iduser=b.ref_iduser)
	WHERE u.iduser='$idpemohon' LIMIT 1");
$pemohon=$u->fetch();

//load data petugas pemeriksa
$pt=$sql->run("SELECT p.nm_lengkap,p.nip,p.jabatan,p.ttd FROM tb_petugas_lap pl LEFT JOIN op_pegawai p ON(pl.ref_idpeg=p.idp) WHERE pl.ref_idp='".$rowbap['ref_idp']."' AND p.status='2'");

$html.='<table style="width:100%">
		<tr>
			<td><img  style="vertical-align: top" src="'.$berkas_admin.'logo-kkp-kop.png" width="100"></td>
			<td style="text-align: center;"><h4><strong>KEMENTERIAN KELAUTAN DAN PERIKANAN</strong></h4>
			<h5>DIREKTORAT JENDERAL PENGELOLAAN RUANG LAUT<h5>
			<h4><strong>BALAI PENGELOLAAN SUMBER DAYA PESISIR DAN LAUT<br/>
			PONTIANAK</strong></h4>
			<small>JALAN HUSEIN HAMZAH NOMOR 01 PAL LIMA, PONTIANAK 78114 TELP.(0561)766691,
			FAX(0561)766465, WEBSITE:bpsplpontianak.kkp.go.id, EMAIL :bpsplpontianak@gmail.com</small></td>
		</tr>
		<tr><td colspan="2"><hr style="margin:0;border:#000"></td></tr>
	</table>
	<br/>';

$html.='<table style="width:100%">
	<tr>
		<td style="text-align: center;">
			<h4><strong><u>BERITA ACARA PEMERIKSAAN BARANG MASUK</u></strong></h4>
			Nomor : '.$rowbap['no_surat'].'
		</td>
	</tr>
	<tr>
		<td style="text-align:justify">
			<p><br/><br/>Pada Hari Ini <strong>'.ucwords($hari).'</strong> tanggal <strong>'.ucwords(terbilang($tgl)).'</strong> Bulan <strong>'.ucwords($bln).'</strong> Tahun <strong>'.ucwords(terbilang($thn)).' ('.$thn.')</strong> bertempat di <strong>'.$rowbap['lokasi'].'</strong>, kami yang bertanda tangan dibawah ini :</p> 
		</td>
	</tr></table>';
$html.='<table style="width:100%">
	<tr>
		<td style="width:5%">1.</td>
		<td style="width:20%">Nama</td>
		<td>: '.$rowbap['nmp1'].'</td>
	</tr>
	<tr>
		<td></td>
		<td>NIP</td>
		<td>: '.$rowbap['nip1'].'</td>
	</tr>
	<tr>
		<td></td>
		<td>Jabatan</td>
		<td>: '.$rowbap['jbtn1'].'</td>
	</tr>
	<tr>
		<td>2.</td>
		<td>Nama</td>
		<td>: '.$rowbap['nmp2'].'</td>
	</tr>
	<tr>
		<td></td>
		<td>NIP</td>
		<td>: '.$rowbap['nip2'].'</td>
	</tr>
	<tr>
		<td></td>
		<td>Jabatan</td>
		<td>: '.$rowbap['jbtn2'].'</td>
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
		<td>: '.ucwords($pemohon['nama_lengkap']).'</td>
	</tr>
	<tr>
		<td></td>
		<td>Alamat</td>
		<td>: '.ucwords($pemohon['alamat']).'</td>
	</tr>
	<tr>
		<td colspan="3" style="text-align:justify">
			<br/>
			<p>'.$rowbap['redaksi'].'</p>
			<br/>
			<p>Demikian Berita Acara Pemeriksaan ini dibuat dengan sebenar-benarnya, untuk dapat dipergunakan sebagaimana mestinya.</p>
		</td>
	</tr>
</table>';

$html.='<table width="100%">
	<tr>
		<td colspan="2" style="text-align: center;">
			<br>
			'.tanggalIndo($rowbap['tgl_surat'],'j F Y').',<br>
			Tim Pemeriksa
		</td>
	</tr>
	<tr>
		<td width="40%" style="text-align: center;">
			<br>
			<img height="85px" src="'.$berkas_admin.$rowbap['ttd1'].'">
			<p>'.$rowbap['nmp1'].'</p>
		</td>
		<td width="60%" style="text-align: center;">
			<br>
			<table>
				<tr>
					<td><img height="150px" src="'.$berkas_admin.'cap-bpspl.png"></td>
					<td><img height="85px" src="'.$berkas_admin.$rowbap['ttd2'].'"><p>'.$rowbap['nmp2'].'</p></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: center;">
			<br>Perwakilan Perusahaan/ Pengirim<br>
			<img height="85px" src="'.$berkas_pemohon.$pemohon['nama_file'].'">
			<p>'.$pemohon['nama_lengkap'].'</p>
		</td>
	</tr>
</table> <pagebreak />';

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
	</tr>';

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
		$html.='<td><img style="padding:10px" width="100%" src="'.$berkas_admin_foto.$gbr['nm_file'].'"><h4>'.$gbr['ket_foto'].'</h4></td>';
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

include("assets/mpdf60/mpdf.php");

$mpdf=new mPDF('','','10','Arial',15,10,15,10,10,10); 
$mpdf->WriteHTML($html);
$mpdf->Output("rekomendasi-".$row['kode_surat'].".pdf",'I');
//$mpdf->Output("rek_files/rekomendasi-".$row['kode_surat'].".pdf",'F');  

exit;