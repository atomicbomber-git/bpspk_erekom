<?php
include ("../../engine/render.php");
$kdsurat=($_GET['rek']);
if(!ctype_digit($kdsurat)){
	exit();
}

if($_GET['token']!=md5($kdsurat.U_ID.'dwsurat_rekomendasi')){
	exit();
}

$location		= c_BASE."berkas/img/";
//require_once(c_THEMES."conf.php");

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
<body>
';
if($rek->rowCount()>0){
	$row=$rek->fetch();
	$html.='<table style="width:100%">
		<tr>
			<td><img  style="vertical-align: top" src="'.$location.'logo-kkp-kop.png" width="100"></td>
			<td style="text-align: center;"><h4><strong>KEMENTERIAN KELAUTAN DAN PERIKANAN</strong></h4>
			<h5>DIREKTORAT JENDERAL PENGELOLAAN RUANG LAUT<h5>
			<h4><strong>BALAI PENGELOLAAN SUMBER DAYA PESISIR DAN LAUT<br/>
			PONTIANAK</strong></h4>
			<small>JALAN HUSEIN HAMZAH NOMOR 01 PAALLIMA, PONTIANAK 78114 TELP.(0561)766691,
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
			<td class="barcodecell"><barcode code="'.$row['kode_surat'].'" height="0.95" text="1" class="barcode" type="C128C" />
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
			<td>Jenis Produk</td>
			<td width="12%">Kemasan</td>
			<td width="12%">No.Segel</td>
			<td width="12%">Berat Ikan(Kg)</td>
			<td>Keterangan</td>
		</tr>';
		$dt=$sql->run("SELECT thp.*, rjs.jenis_sampel FROM tb_rek_hsl_periksa thp JOIN ref_jns_sampel rjs ON (rjs.id_ref=thp.ref_jns) WHERE thp.ref_idrek='".$row['idrek']."' ORDER BY thp.ref_jns ASC");
		if($dt->rowCount()>0){
			$no=0;
			foreach($dt->fetchAll() as $dtrow){
				$no++;
				$html.='
				<tr>
					<td width="5%">'.$no.'</td>
					<td>'.$dtrow['jenis_sampel'].'</td>
					<td>'.$dtrow['kemasan'].' '.$dtrow['satuan'].'</td>
					<td>'.$dtrow['no_segel'].'</td>
					<td>'.$dtrow['berat'].'</td>
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
				<p><img height="150px" src="'.$location.$row['ttd'].'"></p>
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
	</table>';
}

$html.='</body>
</html>';
// echo $html;


include("../../../assets/mpdf60/mpdf.php");

$mpdf=new mPDF('','','10','Arial',15,10,15,10,10,10); 
$mpdf->WriteHTML($html);
$mpdf->Output("rekomendasi-".$row['kode_surat'].".pdf",'I'); 

exit;