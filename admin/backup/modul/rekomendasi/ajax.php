<?php
if($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest'){
	die();
}

include ("../../engine/render.php");;
if($_POST){
	switch (trim(strip_tags($_POST['a']))) {
		case 'dtlist-masuk':
			$aColumns = array('p.penerima','p.tujuan','c.nama_lengkap');

			//table
			$Table = "tb_permohonan p ";
			$Table .= "JOIN tb_userpublic c ON(p.ref_iduser=c.iduser)";

			//limit
			$Limit = "";
			if ( isset($_POST['start']) && $_POST['length'] != -1 ) {
			    $Limit = " LIMIT ".intval($_POST['start']).", ".intval($_POST['length']);
			}
			//order
			$Orders = " ORDER BY p.tgl_pengajuan DESC ";

			$Where=" WHERE p.status IN (1,3) ";
			//cari
			$sSearch = "";
			if ( isset($_POST['cari']) && $_POST['cari']!= '' ) {
			    $str = $_POST['cari'];

			    $sSearch = "AND (";
			    for ( $i=0, $ien=count($aColumns) ; $i<$ien ; $i++ ) {
			        $sSearch .= "".$aColumns[$i]." LIKE '%".$str."%' OR ";
			    }
			    $sSearch = substr_replace( $sSearch, "", -3 );
			    $sSearch .= ')';
			}

			$sCustomFilter="";			

			$q=$sql->run("SELECT COUNT(p.idp) as total FROM $Table ");
			$rtot=$q->fetch();
			$total=$rtot['total'];

			$q=$sql->run("SELECT COUNT(p.idp) as total FROM $Table ".$Where.$sSearch.$sCustomFilter);
			$dbfiltot=$q->fetch();
			$filtertotal=$dbfiltot['total'];

			//data
			$q=$sql->run("SELECT c.nama_lengkap,p.tujuan,p.penerima,p.tgl_pengajuan,p.idp,p.status FROM $Table ".$Where.$sSearch.$sCustomFilter.$Orders.$Limit);
			    
			$output = array(
			    "draw" => intval($_POST['draw']),
			    "recordsTotal" => intval($total),
			    "recordsFiltered" => intval($filtertotal),
			    "data" => array()
			);

			$no=1+$_POST['start'];
			foreach($q->fetchAll() as $data){ 
				$verifikasi="<a class='btn btn-sm btn-primary' href='./verifikasi-data.php?data=".base64_encode($data['idp'])."'>Verifikasi Data</a>";
				if($data['status']==3){
					$stat=" <br><small class='text-alert alert-danger'><em>Ditolak Karena Data Tidak Lengkap</em></small>";
				}else{
					$stat="";
				}
				$users=array(
					$no,
					$data['nama_lengkap'].$stat,
					tanggalIndo($data['tgl_pengajuan'],'j F Y H:i'),
					$data['penerima']."<br/>".$data['tujuan'],
					$verifikasi
				);
				$output['data'][] = $users;
				$no++;
			}
			echo json_encode($output);
		break;

		case 'dt-tolak':
			$id=base64_decode($_POST['idp']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$sql->update('tb_permohonan',array('status'=>3),array('idp'=>$id));
			if($sql->error==null){
				$isi_pesan=$_POST['isi_pesan'];
				$arr_insert=array(
					"ref_idp"=>$id,
					"pesan"=>$isi_pesan,
					"status"=>3,
					"date_act"=>date('Y-m-d H:i:s')
				);
				$sql->insert('tb_hsl_verifikasi',$arr_insert);
				
				//-----------------------------------
				$up=$sql->run("SELECT p.tgl_pengajuan, p.penerima, p.tujuan,u.nama_lengkap,u.email FROM tb_permohonan p JOIN tb_userpublic u ON(u.iduser=p.ref_iduser) WHERE p.idp='$id' LIMIT 1");
				$ru=$up->fetch();
				$nama_pemohon=$ru['nama_lengkap'];
				$email=$ru['email'];
				$tgl=tanggalIndo($ru['tgl_pengajuan'],'j F Y H:i');
				$penerima=$ru['penerima'];
				$tujuan=$ru['tujuan'];

				require '../../../assets/phpmailer/PHPMailerAutoload.php';
				$isi="<p>".$nama_pemohon.", Permohonan Rekomendasi yang Anda ajukan pada tanggal : ".$tgl." dengan tujuan pengiriman kepada ".$penerima." (".$tujuan.") Sementara Ini Kami Tolak Karena Data yang anda ajukan belum lengkap. Silakan Lengkapi Data Anda.</p>";
				if($isi_pesan!=''){
					$isi.="<p><strong>Catatan :<strong> ".$isi_pesan."</strong><p>";
				}
				$isi.="<p>Demikian Pemberitahuan Ini kami sampaikan, atas perhatiannya kami ucapkan terima kasih.</p>";
				$arr=array(
					"send_to"=>$email,
					"send_to_name"=>$nama_pemohon,
					"subject_email"=>"Harap Perbaiki Data Permohonan Anda - LPSPL Serang",
					"isi_email"=>$isi);
				/* sendMail($arr) */;
				//-----------------------------------
				echo json_encode(array("stat"=>true,"msg"=>"Aksi Berhasil."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal."));
			}
		break;

		case 'dt-terima':
			$id=base64_decode($_POST['idp']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$jlh_petugas=count(array_filter($_POST['petugas']));
			if($jlh_petugas<2){
				echo json_encode(array("stat"=>false,"msg"=>"Minimal 2 Petugas Harus Dipilih."));
				exit();
			}
			
			$log_u=strtoupper(substr(md5(time()),0,5));
			$log_p=strtoupper(substr(md5(time()),6,5));
			$arr_update=array('status'=>2,
				'log_u'=>$log_u,
				'log_p'=>$log_p
				);

			$sql->update('tb_permohonan',$arr_update,array('idp'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Aksi Berhasil."));
				$arr_insert=array(
					"ref_idp"=>$id,
					"pesan"=>"",
					"status"=>2,
					"date_act"=>date('Y-m-d H:i:s')
				);
				$sql->insert('tb_hsl_verifikasi',$arr_insert);
				require '../../../assets/phpmailer/PHPMailerAutoload.php';
				for($x=0;$x<$jlh_petugas;$x++){
					if($_POST['petugas'][$x]!=""){
						$in=array(
						'ref_idp'=>$id,
						'ref_idpeg'=>$_POST['petugas'][$x],
						'date_insert'=>date('Y-m-d H:i:s')
						);
						$sql->insert('tb_petugas_lap',$in);

						//----------email--------------------------
						$sql->get_row('op_pegawai',array('idp'=>$_POST['petugas'][$x]),array('nm_lengkap','nip','email'));
						$rp=$sql->result;
						$nama_petugas=$rp['nm_lengkap'];
						$email_petugas=$rp['email'];

						$up=$sql->run("SELECT p.alamat_gudang, u.nama_lengkap FROM tb_permohonan p JOIN tb_userpublic u ON(u.iduser=p.ref_iduser) WHERE p.idp='$id' LIMIT 1");
						$ru=$up->fetch();
						$nama_pemohon=$ru['nama_lengkap'];
						$alamat_gudang=$ru['alamat_gudang'];

						
						$isi="<p>".$nama_petugas.", Anda telah ditunjuk untuk melakukan pemeriksaan sampel Ikan Pari/Hiu Milik: ".$nama_pemohon." yang berlokasi di ".$alamat_gudang." </p>
						<p>Untuk Pengisian Hasil Pemeriksaan Anda dapat mengunjungi <a href='".c_DOMAIN_UTAMA."pemeriksaan'>".c_DOMAIN_UTAMA."pemeriksaan</a> :<br>
						username : ".$log_u."<br>
						password : ".$log_p."<br/>
						email : ".$email_petugas."</p>
						<p>Untuk Keterangan Lebih Lanjut dapat menghubungi Admin LPSPL Serang, atas perhatiannya diucapkan terima kasih.</p>
						";
						$arr=array(
							"send_to"=>$email_petugas,
							"send_to_name"=>$nama_petugas,
							"subject_email"=>"Penunjukan Petugas Pemeriksaan - LPSPL Serang",
							"isi_email"=>$isi);
						/* sendMail($arr) */;
						//-----------------------------------------
					}
				}
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal."));
			}
		break;

		case 'dtlist-periksa':
			$aColumns = array('p.penerima','p.tujuan','c.nama_lengkap');

			//table
			$Table = "tb_permohonan p ";
			$Table .= "JOIN tb_userpublic c ON(p.ref_iduser=c.iduser)";

			//limit
			$Limit = "";
			if ( isset($_POST['start']) && $_POST['length'] != -1 ) {
			    $Limit = " LIMIT ".intval($_POST['start']).", ".intval($_POST['length']);
			}
			//order
			$Orders = " ORDER BY p.tgl_pengajuan DESC ";

			$Where=" WHERE p.status='2' ";
			//cari
			$sSearch = "";
			if ( isset($_POST['cari']) && $_POST['cari']!= '' ) {
			    $str = $_POST['cari'];

			    $sSearch = "AND (";
			    for ( $i=0, $ien=count($aColumns) ; $i<$ien ; $i++ ) {
			        $sSearch .= "".$aColumns[$i]." LIKE '%".$str."%' OR ";
			    }
			    $sSearch = substr_replace( $sSearch, "", -3 );
			    $sSearch .= ')';
			}

			$sCustomFilter="";			

			$q=$sql->run("SELECT COUNT(p.idp) as total FROM $Table ".$Where);
			$rtot=$q->fetch();
			$total=$rtot['total'];

			$q=$sql->run("SELECT COUNT(p.idp) as total FROM $Table ".$Where.$sSearch.$sCustomFilter);
			$dbfiltot=$q->fetch();
			$filtertotal=$dbfiltot['total'];

			//data
			$q=$sql->run("SELECT c.nama_lengkap,p.tujuan,p.penerima,p.tgl_pengajuan,p.idp FROM $Table ".$Where.$sSearch.$sCustomFilter.$Orders.$Limit);
			    
			$output = array(
			    "draw" => intval($_POST['draw']),
			    "recordsTotal" => intval($total),
			    "recordsFiltered" => intval($filtertotal),
			    "data" => array()
			);

			$no=1+$_POST['start'];
			foreach($q->fetchAll() as $data){ 
				$hsl_pemeriksaan="<a class='btn btn-xs btn-success' href='./form-hasil.php?data=".base64_encode($data['idp'])."'><i class='fa fa-edit'></i> Hasil Pemeriksaan</a>";
				$dokumentasi="<a class='btn btn-xs btn-warning' href='./dokumentasi.php?data=".base64_encode($data['idp'])."'><i class='fa fa-edit'></i> Dokumentasi Pemeriksaan</a>";
				$berita_acara="<a class='btn btn-xs btn-primary' href='./bap.php?data=".base64_encode($data['idp'])."'><i class='fa fa-edit'></i> Berita Acara</a>";
				$rekomendasi="<a class='btn btn-xs btn-info' href='./rekomendasi.php?data=".base64_encode($data['idp'])."'><i class='fa fa-edit'></i> Rekomendasi</a>";

				$aksi=$hsl_pemeriksaan."<br>".$dokumentasi."<br>".$berita_acara."<br>".$rekomendasi;
				
				$sql->get_row('tb_stat_pengesahan',array('ref_idp'=>$data['idp'],'status'=>3),array('pesan'));
				if($sql->num_rows>0){
					$rp=$sql->result;
					$notif="<p class='text-alert alert-danger'>Pengajuan Ditolak Kepala Balai / Plh Kepala Balai, Segera perbaiki data : <br>".$rp['pesan']."</p>";
				}else{
					$notif="";
				}

				$users=array(
					$no,
					$data['nama_lengkap'].$notif,
					tanggalIndo($data['tgl_pengajuan'],'j F Y H:i'),
					$data['penerima']."<br/>".$data['tujuan'],
					$aksi
				);
				$output['data'][] = $users;
				$no++;
			}
			echo json_encode($output);
		break;

		case 'update-dt-periksa':
			if(!is_verifikator()){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$idpengajuan=base64_decode($_POST['idp']);
			if(!ctype_digit($idpengajuan)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$idperiksa=base64_decode($_POST['idpr']);
			if(!ctype_digit($idperiksa)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_update=array(
				'tgl_periksa'=>date('Y-m-d',strtotime($_POST['tgl_pemeriksaan']))
				);
			$sql->update('tb_pemeriksaan',$arr_update,array('id_periksa'=>$idperiksa));

			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal."));
			}
		break;

		case 'add-hsl-periksa':
			if(!is_verifikator()){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$idpengajuan=base64_decode($_POST['idp']);
			if(!ctype_digit($idpengajuan)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$idperiksa=base64_decode($_POST['idpr']);
			if(!ctype_digit($idperiksa)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			if($_POST['jenis_ikan']=='0' OR $_POST['jenis_ikan']==''){
				echo json_encode(array("stat"=>false,"msg"=>"Silakan Pilih Jenis Ikan"));
				exit();
			}

			if($_POST['pjg']=='0' OR $_POST['pjg']==''){
				echo json_encode(array("stat"=>false,"msg"=>"Panjang Sampel Belum Diisi"));
				exit();
			}

			if($_POST['lbr']=='0' OR $_POST['lbr']==''){
				echo json_encode(array("stat"=>false,"msg"=>"Lebar Sampel Belum Diisi"));
				exit();
			}

			if($_POST['berat']=='0' OR $_POST['berat']==''){
				echo json_encode(array("stat"=>false,"msg"=>"Berat Sampel Belum Diisi"));
				exit();
			}


			$asal_komoditas=(($_POST['asal_komoditas_opt']=='lainnya')?$_POST['asal_komoditas']:$_POST['asal_komoditas_opt']);
			$arr_insert2=array(
				'ref_idikan'=>$_POST['jenis_ikan'],
				'ref_idp'=>$idpengajuan,
				'ref_idperiksa'=>$idperiksa,
				'ref_jns_sampel'=>$_POST['jenis_sampel'],
				'pjg'=>$_POST['pjg'],
				'lbr'=>$_POST['lbr'],
				'berat'=>$_POST['berat'],
				'tot_berat'=>$_POST['berat_tot'],
				'ket'=>$_POST['ket'],
				'date_insert'=>date('Y-m-d H:i:s'),
				'asal_komoditas'=>$asal_komoditas,
				'kuantitas'=>$_POST['kemasan']
				);
			$sql->insert('tb_hsl_periksa',$arr_insert2);
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal."));
			}
		break;

		case 'update-hsl-periksa':
			if(!is_verifikator()){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$idpengajuan=base64_decode($_POST['idp']);
			if(!ctype_digit($idpengajuan)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$idhsl=base64_decode($_POST['idhsl']);
			if(!ctype_digit($idhsl)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$asal_komoditas=(($_POST['asal_komoditas_opt']=='lainnya')?$_POST['asal_komoditas']:$_POST['asal_komoditas_opt']);	
			$arr_update=array(
				'ref_idikan'=>$_POST['jenis_ikan'],
				'ref_jns_sampel'=>$_POST['jenis_sampel'],
				'pjg'=>$_POST['pjg'],
				'lbr'=>$_POST['lbr'],
				'berat'=>$_POST['berat'],
				'tot_berat'=>$_POST['berat_tot'],
				'ket'=>$_POST['ket'],
				'asal_komoditas'=>$asal_komoditas,
				'kuantitas'=>$_POST['kemasan']
				);
			$sql->update('tb_hsl_periksa',$arr_update,array('id_per'=>$idhsl));

			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal."));
			}
		break;

		case 'del-hsl-periksa':
			if(!is_verifikator()){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$idhasil=base64_decode($_POST['iddt']);
			if(!ctype_digit($idhasil)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request."));
				exit();
			}

			$sql->delete('tb_hsl_periksa',array('id_per'=>$idhasil));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Dihapus."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'adft': // upload foto
			if(!is_verifikator()){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$idpengajuan=base64_decode($_POST['idp']);
			if(!ctype_digit($idpengajuan)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			include ("../../engine/resize-class.php");
			$filefoto		=$_FILES['file_foto'];
			$ImageName 		= $filefoto['name'];
			$ImageSize 		= $filefoto['size'];
			$TempSrc	 	= $filefoto['tmp_name'];
			$ImageType	 	= $filefoto['type'];
			$location		= c_BASE."berkas/dok_sample/";

			$img_file=get_file_extension($ImageName);
			$ImageFileName=time();
			$ImageExt=$img_file['file_ext'];
			$saved_img_file=$ImageFileName.".".$ImageExt;

			if($ImageSize>2000000){
				echo json_encode(array("stat"=>false,"msg"=>"Ukuran Images Maksimal 2MB."));
				exit();
			}

			if(!isset($ImageName) || !is_uploaded_file($TempSrc)){
				echo json_encode(array("stat"=>false,"msg"=>"Pastikan gambar sudah dipilih"));
				exit();
			}else{
				$img_ttd = new resize($TempSrc);
				$img_ttd -> resizeImage(500, 400, 'auto');
				$img_ttd -> saveImage($location.$saved_img_file, 80);

				$img_ttd = new resize($TempSrc);
				$img_ttd -> resizeImage(100, 100, 'crop');
				$img_ttd -> saveImage($location."thumb_".$saved_img_file, 70);

				$arr_insert=array(
					"ref_idp"=>$idpengajuan,
					"nm_file"=>$saved_img_file,
					"ket_foto"=>$_POST['ket_foto'],
					"file_type"=>$ImageType,
					"date_insert"=>date('Y-m-d H:i:s')
					);

				$sql->insert('tb_dokumentasi',$arr_insert);
				if($sql->error==null){
					echo json_encode(array("stat"=>true,"msg"=>"Foto Berhasil DiSimpan."));
				}else{
					echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
				}
			}
		break;

		case 'upft': //update ket foto
			if(!is_verifikator()){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$idft=base64_decode($_POST['idft']);
			if(!ctype_digit($idft)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$sql->update('tb_dokumentasi',array('ket_foto'=>$_POST['ket']),array('id_dok'=>$idft));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'delft': // hapus foto
			if(!is_verifikator()){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$idft=base64_decode($_POST['idft']);
			if(!ctype_digit($idft)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$sql->get_row('tb_dokumentasi',array('id_dok'=>$idft),array('nm_file'));
			$r=$sql->result;

			$sql->delete('tb_dokumentasi',array('id_dok'=>$idft));
			if($sql->error==null){
				$location		= c_BASE."berkas/dok_sample/";
				@unlink($location.$r['nm_file']);
				@unlink($location."thumb_".$r['nm_file']);
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Dihapus."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'bapsv': //add BAP
			if(!is_verifikator()){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$idpengajuan=base64_decode($_POST['idp']);
			if(!ctype_digit($idpengajuan)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			if($_POST['token']!=md5($idpengajuan.U_ID.'bap')){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_insert=array(
				'ref_idp'=>$idpengajuan,
				'no_surat'=>$_POST['no_surat'],
				'tgl_surat'=>date("Y-m-d", strtotime($_POST['tgl_penetapan'])),
				'lokasi'=>$_POST['lokasi_penetapan'],
				'redaksi'=>$_POST['redaksi_bap'],
				'ptgs1'=>$_POST['ptg1'],
				'ptgs2'=>$_POST['ptg2'],
				'date_insert'=>date('Y-m-d H:i:s')
				);

			$sql->insert('tb_bap',$arr_insert);
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'bapup': //update BAP
			if(!is_verifikator()){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$idbap=base64_decode($_POST['idbap']);
			if(!ctype_digit($idbap)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			if($_POST['token']!=md5($idbap.U_ID.'bap')){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_update=array(
				'no_surat'=>$_POST['no_surat'],
				'tgl_surat'=>date("Y-m-d", strtotime($_POST['tgl_penetapan'])),
				'lokasi'=>$_POST['lokasi_penetapan'],
				'redaksi'=>$_POST['redaksi_bap'],
				'ptgs1'=>$_POST['ptg1'],
				'ptgs2'=>$_POST['ptg2']
			);

			$sql->update('tb_bap',$arr_update,array('id_bap'=>$idbap));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Perubahan Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'reksv': //add draft rekomendasi
			if(!is_verifikator()){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$idpengajuan=base64_decode($_POST['idp']);
			if(!ctype_digit($idpengajuan)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			if($_POST['token']!=md5($idpengajuan.U_ID.'rek')){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_insert=array(
				"ref_idp"=>$idpengajuan,
				"ref_iduser"=>$_POST['tujuan'],
				"ref_bk"=>$_POST['tembusan_bk'],
				"no_surat"=>$_POST['no_surat'],
				"kode_surat"=>time(),
				"perihal"=>$_POST['perihal'],
				"tgl_surat"=>date("Y-m-d", strtotime($_POST['tgl_surat'])),
				"tujuan"=>$_POST['tujuan_nm'],
				"redaksi"=>$_POST['redaksi_rek'],
				"pnttd"=>$_POST['penandatgn'],
				"date_create"=>date('Y-m-d H:i:s')
			);
			$sql->insert('tb_rekomendasi',$arr_insert);
			if($sql->error==null){
				$idrek=$sql->insert_id;
				for($x=0;$x<count($_POST['jenis_sampel']);$x++){
					$arr_insert2=array(
						"ref_idrek"=>$idrek,
						"ref_jns"=>$_POST['jenis_sampel'][$x],
						"kemasan"=>$_POST['kemasan'][$x],
						"satuan"=>$_POST['satuan'][$x],
						"no_segel"=>$_POST['nosegel'][$x],
						"berat"=>$_POST['berat'][$x],
						"keterangan"=>$_POST['keterangan'][$x],
						"date_create"=>date('Y-m-d H:i:s')
						);

					$sql->insert('tb_rek_hsl_periksa',$arr_insert2);
				}
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'rekup': //update draft rekomendasi
			if(!is_verifikator()){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$idrek=base64_decode($_POST['idrek']);
			if(!ctype_digit($idrek)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			if($_POST['token']!=md5($idrek.U_ID.'rek')){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_update=array(
				"no_surat"=>$_POST['no_surat'],
				"perihal"=>$_POST['perihal'],
				"ref_bk"=>$_POST['tembusan_bk'],
				"tgl_surat"=>date("Y-m-d", strtotime($_POST['tgl_surat'])),
				"redaksi"=>$_POST['redaksi_rek'],
				"pnttd"=>$_POST['penandatgn']
			);
			$sql->update('tb_rekomendasi',$arr_update,array('idrek'=>$idrek));
			if($sql->error==null){
				$sql->delete('tb_rek_hsl_periksa',array('ref_idrek'=>$idrek));

				for($x=0;$x<count($_POST['jenis_sampel']);$x++){
					$arr_insert2=array(
						"ref_idrek"=>$idrek,
						"ref_jns"=>$_POST['jenis_sampel'][$x],
						"kemasan"=>$_POST['kemasan'][$x],
						"satuan"=>$_POST['satuan'][$x],
						"no_segel"=>$_POST['nosegel'][$x],
						"berat"=>$_POST['berat'][$x],
						"keterangan"=>$_POST['keterangan'][$x],
						"date_create"=>date('Y-m-d H:i:s')
						);

					$sql->insert('tb_rek_hsl_periksa',$arr_insert2);
				}
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'submit':
			if(!is_verifikator()){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$idrek=base64_decode($_POST['rek']);
			if(!ctype_digit($idrek)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			if($_POST['token']!=md5($idrek.U_ID.'submit')){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			
			$getdb=$sql->run("SELECT tr.ref_idp,op.nm_lengkap, op.email, op.idp FROM tb_rekomendasi tr JOIN op_pegawai op ON(op.nip=tr.pnttd) WHERE tr.idrek='$idrek' LIMIT 1");
			$r=$getdb->fetch();

			$sql->update('tb_permohonan',array('status'=>4),array('idp'=>$r['ref_idp']));
			if($sql->error==null){

				//-- mengisi tabel status pengesahan--//
				$us=$sql->run("SELECT p.nip FROM op_user u JOIN op_pegawai p ON(p.idp=u.ref_idpeg) WHERE u.idu='".U_ID."' AND u.lvl='95' LIMIT 1");
				$verifikator=$us->fetch();

				$sql->get_row('tb_stat_pengesahan',array('ref_idp'=>$r['ref_idp']),'idsp');
				if($sql->num_rows>0){
					$rtsp=$sql->result;
					$arr_up=array(
						"ref_idp"=>$r['ref_idp'],
						"verifikator"=>$verifikator['nip'],
						"tgl_verifikasi"=>date('Y-m-d H:i:s'),
						"status"=>1);
					$sql->update('tb_stat_pengesahan',$arr_up,array('idsp'=>$rtsp['idsp']));
				}else{
					$arr_ins=array(
						"ref_idp"=>$r['ref_idp'],
						"verifikator"=>$verifikator['nip'],
						"tgl_verifikasi"=>date('Y-m-d H:i:s'),
						"pesan"=>"",
						"status"=>1);
					$sql->insert('tb_stat_pengesahan',$arr_ins);
				}
				//---------------email--------------------------
				require '../../../assets/phpmailer/PHPMailerAutoload.php';
				$isi="<p>".$r['nm_lengkap'].", Terdapat Permohonan Rekomendasi perlu diperiksa dan disahkan.</p>";
				$isi.="<p>Silakan Buka Tautan <a target='_blank' href='".c_DOMAIN."modul/rekomendasi/persetujuan.php?token=".md5($r['ref_idp'].$r['idp']."confirm")."&data=".base64_encode($r['ref_idp'])."'>Berikut Ini</a></p>";
				$isi.="<p>Demikian Pemberitahuan Ini kami sampaikan, atas perhatiannya kami ucapkan terima kasih.</p>";
				$arr=array(
					"send_to"=>$r['email'],
					"send_to_name"=>$r['nm_lengkap'],
					"subject_email"=>"Pengesahan Permohonan Rekomendasi - LPSPL Serang",
					"isi_email"=>$isi);
				/* sendMail($arr) */;
				//---------------------------------------------
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			} 
		break;

		case 'dtlist-persetujuan':
			$aColumns = array('p.penerima','p.tujuan','c.nama_lengkap');

			//table
			$Table = "tb_permohonan p ";
			$Table .= "JOIN tb_userpublic c ON(p.ref_iduser=c.iduser)";

			//limit
			$Limit = "";
			if ( isset($_POST['start']) && $_POST['length'] != -1 ) {
			    $Limit = " LIMIT ".intval($_POST['start']).", ".intval($_POST['length']);
			}
			//order
			$Orders = " ORDER BY p.tgl_pengajuan DESC ";

			$Where=" WHERE p.status='4' ";
			//cari
			$sSearch = "";
			if ( isset($_POST['cari']) && $_POST['cari']!= '' ) {
			    $str = $_POST['cari'];

			    $sSearch = "AND (";
			    for ( $i=0, $ien=count($aColumns) ; $i<$ien ; $i++ ) {
			        $sSearch .= "".$aColumns[$i]." LIKE '%".$str."%' OR ";
			    }
			    $sSearch = substr_replace( $sSearch, "", -3 );
			    $sSearch .= ')';
			}

			$sCustomFilter="";			

			$q=$sql->run("SELECT COUNT(p.idp) as total FROM $Table ".$Where);
			$rtot=$q->fetch();
			$total=$rtot['total'];

			$q=$sql->run("SELECT COUNT(p.idp) as total FROM $Table ".$Where.$sSearch.$sCustomFilter);
			$dbfiltot=$q->fetch();
			$filtertotal=$dbfiltot['total'];

			//data
			$q=$sql->run("SELECT c.nama_lengkap,p.tujuan,p.penerima,p.tgl_pengajuan,p.idp FROM $Table ".$Where.$sSearch.$sCustomFilter.$Orders.$Limit);
			    
			$output = array(
			    "draw" => intval($_POST['draw']),
			    "recordsTotal" => intval($total),
			    "recordsFiltered" => intval($filtertotal),
			    "data" => array()
			);

			$no=1+$_POST['start'];
			foreach($q->fetchAll() as $data){ 
				$view="<a class='btn btn-xs btn-success' href='./persetujuan.php?token=".md5($data['idp'].U_ID."confirm")."&data=".base64_encode($data['idp'])."'><i class='fa fa-file-text'></i> Lihat Data</a>";

				$aksi=$view;

				$users=array(
					$no,
					$data['nama_lengkap'],
					tanggalIndo($data['tgl_pengajuan'],'j F Y H:i'),
					$data['penerima']."<br/>".$data['tujuan'],
					$aksi
				);
				$output['data'][] = $users;
				$no++;
			}
			echo json_encode($output);
		break;

		case 'pengesahan':
			if(!is_kepala()){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$idp=base64_decode($_POST['idp']);
			if(!ctype_digit($idp)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			if($_POST['token']!=md5($idp.U_ID.'pengesahan')){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$sql->update('tb_permohonan',array('status'=>5),array('idp'=>$idp));
			if($sql->error==null){
				//------------------email-----------------
				require '../../../assets/phpmailer/PHPMailerAutoload.php';
				$up=$sql->run("SELECT u.nama_lengkap,u.email, tr.no_surat, tr.kode_surat FROM tb_permohonan p 
					JOIN tb_userpublic u ON(u.iduser=p.ref_iduser) 
					JOIN tb_rekomendasi tr ON(tr.ref_idp=p.idp)
					WHERE p.idp='$idp' LIMIT 1");
				$ru=$up->fetch();
				$nama_pemohon=$ru['nama_lengkap'];
				$email=$ru['email'];

				//email ke pemohon
				$isi="<p>".$nama_pemohon.", Permohonan Rekomendasi telah keluar</p>";
				$isi.="<p>Anda dapat mengakses aplikasi E-Rekomendasi untuk mengunduh berkas surat rekomendasi, atau dapat juga melalui link berikut : <a href='".c_DOMAIN_UTAMA."download.php?surat=".$ru['kode_surat']."&token=".md5('download'.$ru['kode_surat'].'public')."' target='_blank'>Download Surat Rekomendasi </a></p>";
				$isi.="<p>Catatan Tambahan :<br>
				Nomor Surat : ".$ru['no_surat']."<br>
				Kode Surat: ".$ru['kode_surat']."</p>";
				$isi.="<p>Demikian Pemberitahuan Ini kami sampaikan, atas perhatiannya kami ucapkan terima kasih.</p>";
				$arr=array(
					"send_to"=>$email,
					"send_to_name"=>$nama_pemohon,
					"subject_email"=>"Rekomendasi - LPSPL Serang",
					"isi_email"=>$isi);
				/* sendMail($arr) */;

				//email arsip ke bpspl
				$sql->get_row('web_setting',array('ws_key'=>'email_bpspl'),'ws_value');
				if($sql->num_rows>0){
					$rr=$sql->result;
					$isi_arsip="<p>Arsip Rekomendasi <br>
					Diajukan Oleh : ".$nama_pemohon." <br/>
					Arsip Dapat Didownload Pada Link Berikut <a href='".c_DOMAIN_UTAMA."download.php?surat=".$ru['kode_surat']."&token=".md5('download'.$ru['kode_surat'].'public')."' target='_blank'>Download Surat Rekomendasi </a></p>";

					$arr=array(
						"send_to"=>$rr['ws_value'],
						"send_to_name"=>"LPSPL Serang",
						"subject_email"=>"Arsip : Rekomendasi - LPSPL Serang",
						"isi_email"=>$isi_arsip);
					/* sendMail($arr) */;
				}

				//email tembusan ke karantina
				$sql->get_row('tb_rekomendasi',array('ref_idp'=>$idp),array('ref_bk','tgl_surat','perihal'));
				if($sql->num_rows>0){
					$rrek=$sql->result;
					$refbk=$rrek['ref_bk'];

					$sql->get_row('ref_balai_karantina',array('idbk'=>$refbk),array('nama','email'));
					if($sql->num_rows>0){
						$rbk=$sql->result;

						$isi_tembusan="<p>Tembusan Surat Rekomendasi LPSPL Serang kepada pemohon rekomendasi: ".$nama_pemohon." pada tanggal ".tanggalIndo($rrek['tgl_surat'],'j F Y').". Surat dapat didownload pada link berikut : <a href='".c_DOMAIN_UTAMA."download.php?surat=".$ru['kode_surat']."&token=".md5('download'.$ru['kode_surat'].'public')."' target='_blank'>Download Surat Rekomendasi </a></p>";
						$arr=array(
						"send_to"=>$rbk['email'],
						"send_to_name"=>"Kepala ".$rbk['nama'],
						"subject_email"=>"Tembusan : Rekomendasi - LPSPL Serang",
						"isi_email"=>$isi_tembusan);
					/* sendMail($arr) */;
					}
				}
				//-----------------------------------
				
				//------------------qrcode----------------
				$tmp_qrocde_dir="../../../assets/images/img_qrcode/";
				require '../../../assets/phpqrcode/phpqrcode.php';
				$url_cek=c_DOMAIN_UTAMA."cek.php?nomor=".$ru['kode_surat'];
				$qrcode_img_name=$ru['kode_surat']."_qr.png";
				QRcode::png($url_cek, $tmp_qrocde_dir.$qrcode_img_name, 'M', 5, 2);
				
				echo json_encode(array("stat"=>true,"msg"=>"Aksi Berhasil."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			} 
		break;

		case 'tolak-pengesahan':
			if(!is_kepala()){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$idp=base64_decode($_POST['idp']);
			if(!ctype_digit($idp)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			if($_POST['token']!=md5($idp.U_ID.'pengesahan')){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			//ambil informasi pengajuan
			$if=$sql->run("SELECT u.nama_lengkap, p.tgl_pengajuan, p.penerima,p.tujuan FROM tb_permohonan p JOIN tb_userpublic u ON(u.iduser=p.ref_iduser) WHERE p.idp='$idp' LIMIT 1");
			$rif=$if->fetch();

			$arr_update=array(
				"pesan"=>$_POST['pesan'],
				"status"=>3);
			$sql->update('tb_stat_pengesahan',$arr_update,array('ref_idp'=>$idp));
			if($sql->error==null){
				$sql->update('tb_permohonan',array('status'=>2),array('idp'=>$idp));
				//--get email verifikator
				$ev=$sql->run("SELECT op.email,op.nm_lengkap FROM tb_stat_pengesahan tsp JOIN op_pegawai op ON(op.nip=tsp.verifikator) WHERE tsp.ref_idp='$idp'");
				if($ev->rowCount()>0){
					$rev=$ev->fetch();
					$nama_verifikator=$rev['nm_lengkap'];
					$email_verifikator=$rev['email'];

					//-kirim email pemberitahuan kepada verifiaktor
					require '../../../assets/phpmailer/PHPMailerAutoload.php';
					$isi_pesan="<p>".$nama_verifikator.", terdapat data/surat yang perlu diperbaiki dari pengajuan pengesahaan yang anda ajukan kepada Kepala Balai/plh Kepala Balai untuk permohonan rekomendasi dari:</p>";
					$isi_pesan.='<table border="0">
						<tr>
							<td>Pemohon</td>
							<td>'.$rif['nama_lengkap'].'</td>
						</tr>
						<tr>
							<td>Tanggal Pengajuan</td>
							<td>'.tanggalIndo($rif['tgl_pengajuan'],"j F Y H:i").'</td>
						</tr>
						<tr>
							<td>Tujuan</td>
							<td>'.$rif['penerima'].'<br/>'.$rif['tujuan'].'</td>
						</tr>
						<tr>
							<td>Catatan :</td>
							<td>Pesan dari Kepala Balai/Plh Kepala Balai : <p>'.$_POST['pesan'].'</p></td>
						</tr>
					</table>';
					$isi_pesan.="<p>Demikian Pemberitahuan Ini kami sampaikan, atas perhatiannya kami ucapkan terima kasih.</p>";
					$arr=array(
					"send_to"=>$email_verifikator,
					"send_to_name"=>$nama_verifikator,
					"subject_email"=>"Perbaikan Surat/Data- LPSPL Serang",
					"isi_email"=>$isi_pesan);

					/* sendMail($arr) */;
				}

				echo json_encode(array("stat"=>true,"msg"=>"Aksi Berhasil."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			} 
		break;

		default:
			echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
			exit();
		break;
	}
}

function is_admin(){
	if(U_LEVEL==100){
		return true;
	}else{
		return false;
	}
}

function is_kepala(){
	if(U_LEVEL==90 OR U_LEVEL==91){
		return true;
	}else{
		return false;
	}
}

function is_verifikator(){
	if(U_LEVEL==95){
		return true;
	}else{
		return false;
	}
}