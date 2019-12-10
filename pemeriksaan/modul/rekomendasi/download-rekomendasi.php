<?php

use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;

include ("../../engine/render.php");
$kdsurat=($_GET['rek']);
if(!ctype_digit($kdsurat)){
	exit();
}

if($_GET['token']!=md5($kdsurat.U_ID.'dwsurat_rekomendasi')){
	exit();
}

$location= c_BASE_UTAMA."admin/berkas/img/";
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
			<td class="barcodecell">
			<p>'.$row['kode_surat'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<p></td>
		</tr>
	</table>';
	$html.='<table style="width:100%">
		<tr>
			<td style="text-align:justify;"><br>
			<p>Menindaklanjuti Surat Saudara tanggal '.tanggalIndo($row['tgl_pengajuan'],'j F Y').' perihal permohonan rekomendasi untuk lalu lintas hiu/pari ke '.$row['tujuan'].' melalui jalur '.ucwords($row['jenis_angkutan']).', dengan ini disampaikan bahwa Petugas Loka Pengelolaan Sumberdaya Pesisir dan Laut Serang telah melakukan identifikasi yang tertuang dalam Berita Acara Nomor : '.$row['nobap'].' tanggal '.tanggalIndo($row['tglbap'],'j F Y').' dengan hasil:</p>
			</td>
		</tr>
	</table>';
	$html.='<table style="width:100%" class="table table-bordered">
    <thead style="background: rgba(0, 0, 0, 0.1)">
        <tr>
            <td style="text-align:center" width="5%">No</td>
            <td style="text-align:center"> Nama Ikan / Barang </td>
            <td style="text-align:center" width="12%"> Jenis Produk </td>
            <td style="text-align:center" width="12%"> Berat (kg) </td>
            <td style="text-align:center" width="12%"> Jumlah Kemasan </td>
            <td style="text-align:center"> No. Segel </td>
            <td style="text-align:center"> Keterangan </td>
        </tr>
    </thead>';
		$dt=$sql->run("SELECT 
		thp.*, 
		rjs.jenis_sampel,
		rdi.nama_latin,
		satuan_barang.nama AS nama_satuan_barang

		FROM tb_rek_hsl_periksa 
		thp LEFT JOIN ref_jns_sampel rjs ON (rjs.id_ref=thp.ref_jns) 
		LEFT JOIN ref_data_ikan rdi ON(rdi.id_ikan=thp.ref_idikan) 
		LEFT JOIN satuan_barang ON (thp.id_satuan_barang = satuan_barang.id)
		
		WHERE thp.ref_idrek='".$row['idrek']."' ORDER BY thp.ref_jns ASC");
		
		$total_berat = 0;
		
		if($dt->rowCount()>0){
			$no=0;
			$jlh_berat=0;
			foreach($dt->fetchAll() as $dtrow){
				$no++;

				$total_berat += ($berat = ($dtrow['berat'] ?: 0));
				
				$html.='
				<tr>
					<td style="text-align: center"  width="5%">'.$no.'</td>
					<td style="text-align: center"><em>'.$dtrow['nama_latin'].'</em></td>
					<td style="text-align: center"> '.$dtrow['produk'].' '.$dtrow['jenis_produk'].' '.$dtrow['kondisi_produk'].'</td>
					<td style="text-align: center">'. $berat .'</td>
					<td style="text-align: center">'.$dtrow['kemasan'].' '.$dtrow['nama_satuan_barang'].'</td>
					<td style="text-align: center">'.$dtrow['no_segel'].'-'.$dtrow['no_segel_akhir'].'</td>
					<td style="text-align: center">'.$dtrow['keterangan'].'</td>
				</tr>';
			}
			
		}
		$html.='
			<tr>
				<td style="text-align: center; font-weight: bold" colspan="3">
					Total Berat:
				</td>
				<td style="text-align: center">
					'. $total_berat .'
				</td>
				<td> </td>
				<td> </td>
				<td> </td>
			</tr>';
		
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
				'.(($row['lvl']==90)?"Kepala Loka":"Plh. Kepala Loka").'
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

$mpdf = container(Mpdf::class);
$mpdf->WriteHTML(file_get_contents(container("mpdf_css_path")), HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);
$mpdf->Output("rekomendasi-{$row['kode_surat']}.pdf", 'I');
