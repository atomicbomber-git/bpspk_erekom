<?php
include ("../../engine/render.php");

if($_GET){
	switch (trim(strip_tags($_GET['t']))) {
		case 'month':

			$bulan=$_GET['bln'];
			$tahun=$_GET['thn'];
			if(!ctype_digit($bulan)){
				echo 'Not Found';
				exit();
			}
			if(!ctype_digit($tahun)){
				echo 'Not Found';
				exit();
			}

			$zipname = c_BASE_UTAMA.'dokumentasi.zip';
			$lokasi_berkas = c_BASE_UTAMA.'admin/berkas/dok_sample/';

			$filter=$tahun.'-'.$bulan;
			$dt=$sql->run("SELECT td.nm_file,td.ket_foto,td.file_type,tr.no_surat FROM tb_dokumentasi td JOIN tb_rekomendasi tr ON (tr.ref_idp = td.ref_idp) WHERE DATE_FORMAT(tr.tgl_surat,'%Y-%m') ='".$filter."' ORDER BY tr.no_surat DESC,tr.tgl_surat DESC");
			if($dt->rowCount()>0){
				
				if (file_exists($zipname)) {
					@unlink($zipname);
				}
				$zip = new ZipArchive();
				$zip->open($zipname, ZipArchive::CREATE);
				foreach($dt->fetchAll() as $r){
					if(file_exists($lokasi_berkas.$r['nm_file'])){
						$zip->addFile($lokasi_berkas.$r['nm_file'],str_replace('/', '-', trim($r['no_surat'])).'/'.$r['nm_file']);
					}
				}
				$zip->close();

				if (file_exists($zipname)) {
					echo "downloading file...";
					header('Content-Description: dokumentasi-'.$bulan.'-'.$tahun);
					header('Content-type: application/zip');
					header('Content-Disposition: attachment; filename="'.basename($zipname).'"');
					header("Content-length: " . filesize($zipname));
					header("Pragma: no-cache");
					header("Expires: 0");

					ob_clean();
					flush();
					readfile($zipname);
					//unlink($zipname);
				}
			}else{
				echo "Not Found."; exit();
			}
		break;

		case 'norek':

			$no_surat=base64_decode($_GET['no']);
			$q=$sql->run("SELECT td.nm_file,td.ket_foto,td.file_type,tr.no_surat FROM tb_dokumentasi td JOIN tb_rekomendasi tr ON (tr.ref_idp = td.ref_idp) WHERE tr.no_surat LIKE '".$no_surat."%'");
			
			if($q->rowCount()>0){
				$zipname = c_BASE_UTAMA.'dokumentasi.zip';
				$lokasi_berkas = c_BASE_UTAMA.'admin/berkas/dok_sample/';
				
				if (file_exists($zipname)) {
					@unlink($zipname);
				}
				$zip = new ZipArchive();
				$zip->open($zipname, ZipArchive::CREATE);
				foreach($q->fetchAll() as $r){
					if(file_exists($lokasi_berkas.$r['nm_file'])){
						$zip->addFile($lokasi_berkas.$r['nm_file'],str_replace('/', '-', trim($r['no_surat'])).'/'.$r['nm_file']);
					}
				}
				$zip->close();

				if (file_exists($zipname)) {
					echo "downloading file...";
					header('Content-Description: dokumentasi-'.$bulan.'-'.$tahun);
					header('Content-type: application/zip');
					header('Content-Disposition: attachment; filename="'.basename($zipname).'"');
					header("Content-length: " . filesize($zipname));
					header("Pragma: no-cache");
					header("Expires: 0");

					ob_clean();
					flush();
					readfile($zipname);
					//unlink($zipname);
				}

			}else{
				echo "Not Found."; exit();
			}
		break;

		default:
			echo "Not Found.";
		break;
	}
}
?>