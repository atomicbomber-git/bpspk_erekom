<?php
include ("../../engine/render.php");

$filter=$_POST['filter_tahun'].'-'.$_POST['filter_bulan'];

$ref_satker=array(
"4"=>"Balikpapan",
"5"=>"Pontianak",
"6"=>"Banjarmasin",
"7"=>"Tarakan",
"8"=>"Balikpapan",
"9"=>"Balikpapan",
);

$hari_id = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu");

$l=$sql->run("SELECT trhp.ref_idrek, trhp.ref_idikan, trhp.ref_jns, rdi.nama_ikan,rdi.nama_latin,rdi.dilindungi,rdi.peredaran,rdi.ket_dasarhukum, rjs.jenis_sampel
		FROM tb_rek_hsl_periksa trhp
		LEFT JOIN ref_data_ikan rdi ON (rdi.id_ikan = trhp.ref_idikan)
		LEFT JOIN ref_jns_sampel rjs ON (rjs.id_ref = trhp.ref_jns)
		WHERE ref_idrek IN (SELECT idrek FROM tb_rekomendasi WHERE DATE_FORMAT(tgl_surat,'%Y-%m') ='".$filter."') 
		ORDER BY trhp.ref_idrek, trhp.ref_idikan  ");
	$produk=array();
	
	foreach($l->fetchAll() as $prd){
		$dasar_hukum="";
		if($prd['dilindungi']=='1'){
			if($prd['ket_dasarhukum']!=""){
				$dasar_hukum="sesuai dengan ".$prd['ket_dasarhukum'];
			}
			$produk[$prd['ref_idrek']]['ikan_dilindungi'][$prd['peredaran']][]=$prd['nama_ikan']."(".$prd['nama_latin'].") ".$dasar_hukum;
		}else{
			$produk[$prd['ref_idrek']]['ikan_takdilindungi'][$prd['peredaran']][]=$prd['nama_ikan']."(".$prd['nama_latin'].")";
		}
		
		$produk[$prd['ref_idrek']]['jns_produk'][]=$prd['jenis_sampel'];
	}

// echo'<pre>';
// 	// $test = array_unique($produk[675]['jns_produk']);
// 	// print_r($test);
	// print_r($produk);

// exit();

$sq1 = "SELECT 
	tr.idrek,tr.ref_idp, tr.ref_iduser, tr.ref_satker, tr.no_surat, tr.tgl_surat, tr.tujuan,tu.nama_lengkap, tp.tujuan,bap.no_surat as bap,
	(SELECT SUM(berat)FROM tb_rek_hsl_periksa WHERE ref_idrek = tr.idrek)  as berat_jumlah ,
	(SELECT SUM(kemasan) FROM tb_rek_hsl_periksa WHERE ref_idrek = tr.idrek)  as koli
	FROM tb_rekomendasi tr 
	LEFT JOIN tb_userpublic tu ON (tu.iduser = tr.ref_iduser)
	LEFT JOIN tb_permohonan tp ON (tp.idp = tr.ref_idp)
	LEFT JOIN tb_bap bap ON (bap.ref_idp = tp.idp) 
	WHERE DATE_FORMAT(tr.tgl_surat,'%Y-%m') ='".$filter."' ORDER BY tr.tgl_surat ASC";

$result1= $sql->run($sq1);
if($result1->rowCount()>0){
	foreach($result1->fetchAll() as $dt){
		
		$no_surat_rekom =$dt['no_surat'];
		$no_surat_ba =$dt['bap'];
		$tempat = $ref_satker[$dt['ref_satker']];
		$nama_pemohon = $dt['nama_lengkap'];
		$tujuan = $dt['tujuan'];
		$total_berat = $dt['berat_jumlah'];
		$koli = $dt['koli'];

		$hari_permohonan = $hari_id[tanggalIndo($dt['tgl_surat'], 'w')];
		$tgl_permohonan = tanggalIndo($dt['tgl_surat'], '(j/n)');
		
		$sampel=array_unique($produk[$dt['idrek']]['jns_produk']);
		$nama_produk = implode(', ', $sampel);

		$list_tidakdilindungi = array_unique($produk[$dt['idrek']]['ikan_takdilindungi'][1]);
		$list_dilindungi_dilarang_ekspor = array_unique($produk[$dt['idrek']]['ikan_dilindungi'][2]);
		$list_dilindungi_penuh = array_unique($produk[$dt['idrek']]['ikan_dilindungi'][3]);

		if(isset($list_tidakdilindungi) && count($list_tidakdilindungi > 0)){
			$text_hiupari = implode(', ', $list_tidakdilindungi);
			$text_tidakdilindungi = "jenis ".$text_hiupari." yang tidak termasuk kedalam jenis hiu/pari yang dilindungi " ;
		}else{
			$text_tidakdilindungi = "";
		}

		if(isset($list_dilindungi_dilarang_ekspor) && count($list_dilindungi_dilarang_ekspor)> 0 ){
			$text_dilindungi_dilarangekspor ="";
			$text_hiupari2 = implode(', ', $list_dilindungi_dilarang_ekspor);
			if($text_tidakdilindungi!=""){
				$text_dilindungi_dilarangekspor .="sedangkan ";
			}
			$text_dilindungi_dilarangekspor .= " ".$text_hiupari2.", termasuk kedalam jenis yang diatur peredarannya terbatas dalam negeri ";
		}else{
			$text_dilindungi_dilarangekspor ="";
		}

		if(isset($list_dilindungi_penuh) && count($list_dilindungi_penuh)> 0 ){
			$text_dilindungi_penuh ="";
			$text_hiupari3 = implode(', ', $list_dilindungi_penuh);
			if($text_tidakdilindungi!="" OR $text_dilindungi_dilarangekspor!=""){
				$text_dilindungi_penuh .="sedangkan ";
			}
			$text_dilindungi_penuh = " ".$text_hiupari3.", termasuk kedalam jenis yang dilindungi penuh sehingga peredarannya dilarang ";
		}else{
			$text_dilindungi_penuh ="";
		}

		$redaksi = $text_tidakdilindungi." ".$text_dilindungi_dilarangekspor." ".$text_dilindungi_penuh;

		$tabel="";
		$tabel.="<table>";
		$tabel.="<tr>
			<td width='15%'>Tempat</td>
			<td>: $tempat</td>
			</tr>";
		$tabel.="<tr>
			<td>Pihak Terkait</td>
			<td>: BPSPL PONTIANAK dan $nama_pemohon</td>
		</tr>";

		$tabel.="<tr>
			<td colspan='2'>
			BPSPL Pontianak terbitkan Rekomendasi Perdagangan Hiu dan Pari<br>
			".$tempat." - Menindaklanjuti surat dari ".$nama_pemohon." perihal permohonan rekomendasi untuk lalu lintas hiu/pari ke ".$tujuan.", ".$hari_permohonan." ".$tgl_permohonan." telah dilakukan pemeriksaan sampel ".$nama_produk." hiu/pari dengan berat keseluruhan ".$total_berat." Kg sebanyak ".$koli." koli oleh Petugas BPSPL Pontianak, yang tertuang dalam berita acara pemeriksaan No : ".$no_surat_ba."<br/>
			Berdasarkan hasil pemeriksaan sampel tersebut, diketahui bahwa ".$nama_produk." hiu/pari yang akan dikirim oleh ".$nama_pemohon.", teridentifikasi merupakan ".$redaksi." sehingga dapat direkomendasikan peredarannya berdasarkan surat rekomendasi yang telah diterbitkan BPSPL Pontianak No : ".$no_surat_rekom."
			</td>
		</tr>";
		$tabel.="</table><br/>";

		echo $tabel;
	}
}else{
	echo "tidak ada data";
}




?>