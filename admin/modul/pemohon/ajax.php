<?php
if($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest'){
	die();
}

include ("../../engine/render.php");;
if($_POST){
	switch (trim(strip_tags($_POST['a']))) {
		default:
			echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
			exit();
		break;

		case 'list-pemohon':
			$aColumns = array('u.nama_lengkap');

			//table
			$Table = "tb_userpublic u ";
			$Table .= "LEFT JOIN tb_biodata b ON(b.ref_iduser=u.iduser) ";
			$Table .= "LEFT JOIN web_meta reg ON(reg.meta_key='U_REGISTERED' AND reg.meta_group='1' AND reg.ref_id=u.iduser) ";

			//limit
			$Limit = "";
			if ( isset($_POST['start']) && $_POST['length'] != -1 ) {
			    $Limit = " LIMIT ".intval($_POST['start']).", ".intval($_POST['length']);
			}
			//order
			$Orders = " ORDER BY u.nama_lengkap ASC ";

			$Where=" WHERE 1 ";
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

			$q=$sql->run("SELECT COUNT(u.iduser) as total FROM $Table ");
			$rtot=$q->fetch();
			$total=$rtot['total'];

			$q=$sql->run("SELECT COUNT(u.iduser) as total FROM $Table ".$Where.$sSearch.$sCustomFilter);
			$dbfiltot=$q->fetch();
			$filtertotal=$dbfiltot['total'];

			//data
			$q=$sql->run("SELECT u.verifikasi, u.iduser,u.nama_lengkap,u.email,b.*,reg.meta_value as tgl_daftar FROM $Table ".$Where.$sSearch.$sCustomFilter.$Orders.$Limit);
			    
			$output = array(
			    "draw" => intval($_POST['draw']),
			    "recordsTotal" => intval($total),
			    "recordsFiltered" => intval($filtertotal),
			    "data" => array()
			);

			$no=1+$_POST['start'];
			foreach($q->fetchAll() as $data){ 
				$aksi="<a class='btn btn-sm btn-primary' href='./biodata.php?data=".base64_encode($data['iduser'])."'>Biodata</a>";
				$tglregistrasi=date('Y-m-d H:i:s',$data['tgl_daftar']);
				$sql->order_by="";
				$sql->order_by="tgl_pengajuan DESC";
				$sql->get_row('tb_permohonan',array('ref_iduser'=>$data['iduser']),'tgl_pengajuan');
				if($sql->num_rows>0){
					$ph=$sql->result;
					$pengajuanterakhir=tanggalIndo($ph['tgl_pengajuan'],'j F Y H:i');
				}else{
					$pengajuanterakhir="Belum Pernah Mengajukan Permohonan";
				}
				//$pengajuanterakhir="Belum Pernah Mengajukan Permohonan";
				$users=array(
					$no,
					$data['nama_lengkap'] . " ({$data["verifikasi"]})",
					tanggalIndo($tglregistrasi,'j F Y H:i'),
					$pengajuanterakhir,
					$aksi
				);
				$output['data'][] = $users;
				$no++;
			}
			echo json_encode($output);
		break;

		case 'list-riwayat': //list pengajuan
			$aColumns = array('p.penerima','p.tujuan');

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

			$Where=" WHERE p.ref_iduser='".$_POST['u']."' ";
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
			$q=$sql->run("SELECT c.nama_lengkap,p.tujuan,p.penerima,p.tgl_pengajuan,p.idp,p.status,p.ref_iduser,p.tgl_pelayanan, p.no_antrian FROM $Table ".$Where.$sSearch.$sCustomFilter.$Orders.$Limit);
			    
			$output = array(
			    "draw" => intval($_POST['draw']),
			    "recordsTotal" => intval($total),
			    "recordsFiltered" => intval($filtertotal),
			    "data" => array()
			);

			$no=1+$_POST['start'];
			$arr_status=array(
				1=>"Pemeriksaan Data Oleh Admin.",
				2=>"Data Diterima, Pengajuan Sedang Diproses Oleh Admin.",
				3=>"Data Ditolak, Berkas/Data Tidak Lengkap.",
				4=>"Pemeriksaan Barang/Sampel Telah Dilakukan.",
				5=>"Surat Rekomendasi Sudah Diterbitkan."
			);

			foreach($q->fetchAll() as $data){ 
				$aksi="<a class='btn btn-sm btn-primary' href='./detail-pengajuan.php?data=".base64_encode($data['idp'])."&u=".base64_encode($data['ref_iduser'])."'>Detail Data</a>";
				
				$users=array(
					$no,
					$data['penerima']."<br/>".$data['tujuan'],
					tanggalIndo($data['tgl_pengajuan'],'j F Y H:i'),
					format_noantrian($data['tgl_pelayanan'],$data['no_antrian']),
					$arr_status[$data['status']],
					$aksi
				);
				$output['data'][] = $users;
				$no++;
			}
			echo json_encode($output);
		break;

		case 'gantipegpem':
			$idpengajuan=$_POST['idp'];
			$idtblptgs=$_POST['idtb'];
			$pengganti=$_POST['selected_ptgs'];
			if($idpengajuan=="" OR !ctype_digit($idpengajuan)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request(0)"));
				exit();
			}

			if($idtblptgs=="" OR !ctype_digit($idtblptgs)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request(1)"));
				exit();
			}

			if($pengganti=="" OR !ctype_digit($pengganti)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request(2)"));
				exit();
			}

			$sql->update('tb_petugas_lap',array('ref_idpeg'=>$pengganti),array('id_pl'=>$idtblptgs,'ref_idp'=>$idpengajuan));
			if($sql->error==null){
				//send email ke petugas pengganti
				echo json_encode(array("stat"=>true,"msg"=>"Petugas Pemeriksa Berhasil Diganti."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'updatebio';
			$iduser=base64_decode($_POST['p']);
			if(!ctype_digit($iduser)){
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal. Pemohon tidak ditemukan"));
				exit();
			}
			$sql->get_row('tb_biodata',array('ref_iduser'=>$iduser),'idbio');
			if($sql->num_rows>0){
				$row=$sql->result;
				$id=$row['idbio'];
				
				$sql->get_row('tb_berkas',array('ref_iduser'=>$iduser,'jenis_berkas'=>1),'idb');
				if($sql->num_rows<1){
					if(!isset($_FILES['ttd']['name']) OR !is_uploaded_file($_FILES['ttd']['tmp_name'])){
						header('Content-Type: application/json');
						echo json_encode(array("stat"=>false,"msg"=>"Silakan Upload Tandatangan anda."));
						exit();
					}
				}

				
				$tmp_lahir=$_POST['tmp_lahir'];
				$tgl_lahir=date("Y-m-d", strtotime($_POST['tgl_lahir']));;
				$no_ktp=$_POST['no_ktp'];
				$no_telp=$_POST['no_telp'];
				$alamat_rmh=$_POST['alamat_rmh'];
				$npwp=$_POST['npwp'];
				$nm_perusahaan=$_POST['nm_perusahaan'];
				$nib=$_POST['nib'];
				$sipji=$_POST['sipji'];
				$siup=$_POST['siup'];
				$izin_lainnya=$_POST['izin_lainnya'];
				$date_=date('Y-m-d H:i:s');

				$arr_update=array(
					"tmp_lahir"=>$tmp_lahir,
					"tgl_lahir"=>$tgl_lahir,
					"no_telp"=>$no_telp,
					"no_ktp"=>$no_ktp,
					"alamat"=>$alamat_rmh,
					"npwp"=>$npwp,
					"nm_perusahaan"=>$nm_perusahaan,
					"siup"=>$siup,
					"nib" => $nib,
					"sipji" => $sipji,
					"izin_lain"=>$izin_lainnya,
					"gudang_1" => $_POST["gudang_1"],
					"gudang_2" => $_POST["gudang_2"] ?? null,
					"gudang_3" => $_POST["gudang_3"] ?? null,
				);

				$sql->update('tb_biodata',$arr_update,array('idbio'=>$id));


				include("../../engine/resize-class.php");
				$location		= c_BASE_UTAMA."pengajuan/berkas/";
				$npwp=$_FILES['file_npwp'];
				$ktp=$_FILES['file_ktp'];
				$siup=$_FILES['siup'];
				$nib = $_FILES['nib'];
				$sipji = $_FILES['sipji'];
				$ttd=$_FILES['ttd'];
				
				//npwp
				if(isset($npwp['name']) AND is_uploaded_file($npwp['tmp_name'])){
					list($CurWidth,$CurHeight)=getimagesize($npwp['tmp_name']);
					$npwpnm=get_file_extension($npwp['name']);
					$npwp_filename=$iduser.'npwp_'.time();
					$npwp_ext=$npwpnm['file_ext'];
					$saved=$npwp_filename.'.'.$npwp_ext;

					$original = new resize($npwp['tmp_name']);
					$original -> resizeImage($CurWidth, $CurHeight, 'exact');
					$original -> saveImage($location.$saved, 75);

					$sql->get_row('tb_berkas',array('ref_iduser'=>$iduser,'jenis_berkas'=>2),array('revisi'));
					$sql->order_by="date_upload DESC, idb DESC";
					if($sql->num_rows>0){
						$n=$sql->result;
						$rev=$n['revisi'];
						$revisi=$rev+1;
					}else{
						$revisi=0;
					}
					$arr_insert=array(
						"ref_iduser"=>$iduser,
						"nama_file"=>$saved,
						"type_file"=>$npwp['type'],
						"jenis_berkas"=>2,
						"date_upload"=>date('Y-m-d H:i:s'),
						"revisi"=>$revisi
						);

					$sql->insert('tb_berkas',$arr_insert);
				}

				//siup
				if(isset($siup['name']) AND is_uploaded_file($siup['tmp_name'])){
					list($CurWidth,$CurHeight)=getimagesize($siup['tmp_name']);
					$siupnm=get_file_extension($siup['name']);
					$siup_filename=$iduser.'siup_'.time();
					$siup_ext=$siupnm['file_ext'];
					$saved=$siup_filename.'.'.$siup_ext;

					$original = new resize($siup['tmp_name']);
					$original -> resizeImage($CurWidth, $CurHeight, 'exact');
					$original -> saveImage($location.$saved, 75);

					$sql->get_row('tb_berkas',array('ref_iduser'=>$iduser,'jenis_berkas'=>3),array('revisi'));
					$sql->order_by="date_upload DESC, idb DESC";
					if($sql->num_rows>0){
						$n=$sql->result;
						$rev=$n['revisi'];
						$revisi=$rev+1;
					}else{
						$revisi=0;
					}
					$arr_insert=array(
						"ref_iduser"=>$iduser,
						"nama_file"=>$saved,
						"type_file"=>$siup['type'],
						"jenis_berkas"=>3,
						"date_upload"=>date('Y-m-d H:i:s'),
						"revisi"=>$revisi
						);

					$sql->insert('tb_berkas',$arr_insert);
				}


				//nib
				if (isset($nib['name']) and is_uploaded_file($nib['tmp_name'])) {
					list($CurWidth, $CurHeight) = getimagesize($nib['tmp_name']);
					$nibnm = get_file_extension($nib['name']);
					$nib_filename = $iduser . 'nib_' . time();
					$nib_ext = $nibnm['file_ext'];
					$saved = $nib_filename . '.' . $nib_ext;

					$original = new resize($nib['tmp_name']);
					$original->resizeImage($CurWidth, $CurHeight, 'exact');
					$original->saveImage($location . $saved, 75);

					$sql->get_row('tb_berkas', array('ref_iduser' => $iduser, 'jenis_berkas' => 5), array('revisi'));
					$sql->order_by = "date_upload DESC, idb DESC";
					if ($sql->num_rows > 0) {
						$n = $sql->result;
						$rev = $n['revisi'];
						$revisi = $rev + 1;
					} else {
						$revisi = 0;
					}
					$arr_insert = array(
						"ref_iduser" => $iduser,
						"nama_file" => $saved,
						"type_file" => $nib['type'],
						"jenis_berkas" => 5,
						"date_upload" => date('Y-m-d H:i:s'),
						"revisi" => $revisi
					);

					$sql->insert('tb_berkas', $arr_insert);
				}

				//sipji
				if (isset($sipji['name']) and is_uploaded_file($sipji['tmp_name'])) {
					list($CurWidth, $CurHeight) = getimagesize($sipji['tmp_name']);
					$sipjinm = get_file_extension($sipji['name']);
					$sipji_filename = $iduser . 'sipji_' . time();
					$sipji_ext = $sipjinm['file_ext'];
					$saved = $sipji_filename . '.' . $sipji_ext;

					$original = new resize($sipji['tmp_name']);
					$original->resizeImage($CurWidth, $CurHeight, 'exact');
					$original->saveImage($location . $saved, 75);

					$sql->get_row('tb_berkas', array('ref_iduser' => $iduser, 'jenis_berkas' => 6), array('revisi'));
					$sql->order_by = "date_upload DESC, idb DESC";
					if ($sql->num_rows > 0) {
						$n = $sql->result;
						$rev = $n['revisi'];
						$revisi = $rev + 1;
					} else {
						$revisi = 0;
					}
					$arr_insert = array(
						"ref_iduser" => $iduser,
						"nama_file" => $saved,
						"type_file" => $sipji['type'],
						"jenis_berkas" => 6,
						"date_upload" => date('Y-m-d H:i:s'),
						"revisi" => $revisi
					);

					$sql->insert('tb_berkas', $arr_insert);
				}

				//ktp
				if(isset($ktp['name']) AND is_uploaded_file($ktp['tmp_name'])){
					list($CurWidth,$CurHeight)=getimagesize($ktp['tmp_name']);
					$ktpnm=get_file_extension($ktp['name']);
					$ktp_filename=$iduser.'ktp_'.time();
					$ktp_ext=$ktpnm['file_ext'];
					$saved=$ktp_filename.'.'.$ktp_ext;

					$original = new resize($ktp['tmp_name']);
					$original -> resizeImage($CurWidth, $CurHeight, 'exact');
					$original -> saveImage($location.$saved, 75);

					$sql->get_row('tb_berkas',array('ref_iduser'=>$iduser,'jenis_berkas'=>4),array('revisi'));
					$sql->order_by="date_upload DESC, idb DESC";
					if($sql->num_rows>0){
						$n=$sql->result;
						$rev=$n['revisi'];
						$revisi=$rev+1;
					}else{
						$revisi=0;
					}
					$arr_insert=array(
						"ref_iduser"=>$iduser,
						"nama_file"=>$saved,
						"type_file"=>$ktp['type'],
						"jenis_berkas"=>4,
						"date_upload"=>date('Y-m-d H:i:s'),
						"revisi"=>$revisi
						);

					$sql->insert('tb_berkas',$arr_insert);
				}

				//ttd
				if(isset($ttd['name']) AND is_uploaded_file($ttd['tmp_name'])){
					list($CurWidth,$CurHeight)=getimagesize($ttd['tmp_name']);
					$ttdnm=get_file_extension($ttd['name']);
					$ttd_filename=$iduser.'ttd_'.time();
					$ttd_ext=$ttdnm['file_ext'];
					$saved=$ttd_filename.'.'.$ttd_ext;

					$original = new resize($ttd['tmp_name']);
					$original -> resizeImage(300, 200, 'auto');
					$original -> saveImage($location.$saved, 75);

					$sql->get_row('tb_berkas',array('ref_iduser'=>$iduser,'jenis_berkas'=>1),array('revisi'));
					$sql->order_by="date_upload DESC, idb DESC";
					if($sql->num_rows>0){
						$n=$sql->result;
						$rev=$n['revisi'];
						$revisi=$rev+1;
					}else{
						$revisi=0;
					}
					$arr_insert=array(
						"ref_iduser"=>$iduser,
						"nama_file"=>$saved,
						"type_file"=>$ttd['type'],
						"jenis_berkas"=>1,
						"date_upload"=>date('Y-m-d H:i:s'),
						"revisi"=>$revisi
						);

					$sql->insert('tb_berkas',$arr_insert);
				}

				if($sql->error==null){
					header('Content-Type: application/json');
					echo json_encode(array("stat"=>true,"msg"=>"Perubahan Data Berhasil Disimpan."));
					exit();
				}else{
					header('Content-Type: application/json');
					echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal."));
					exit();
				}
			}else{
				header('Content-Type: application/json');
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Action."));
				exit();
			}
		break;

		case 'resetpwd':
		break;

		case 'list-kuisioner':
			//table
			$Table = "tb_kuisioner_s s";

			//limit
			$Limit = "";
			if ( isset($_POST['start']) && $_POST['length'] != -1 ) {
			    $Limit = " LIMIT ".intval($_POST['start']).", ".intval($_POST['length']);
			}
			//order
			$Orders = " ORDER BY s.q_answered DESC ";

			$Where=" WHERE s.ref_idpemohon='".$_POST['u']."' ";
			//cari
			$sSearch = "";
			/*if ( isset($_POST['cari']) && $_POST['cari']!= '' ) {
			    $str = $_POST['cari'];

			    $sSearch = "AND (";
			    for ( $i=0, $ien=count($aColumns) ; $i<$ien ; $i++ ) {
			        $sSearch .= "".$aColumns[$i]." LIKE '%".$str."%' OR ";
			    }
			    $sSearch = substr_replace( $sSearch, "", -3 );
			    $sSearch .= ')';
			}*/

			$sCustomFilter="";			

			$q=$sql->run("SELECT COUNT(s.id_s) as total FROM $Table ".$Where);
			$rtot=$q->fetch();
			$total=$rtot['total'];

			$q=$sql->run("SELECT COUNT(s.id_s) as total FROM $Table ".$Where.$sSearch.$sCustomFilter);
			$dbfiltot=$q->fetch();
			$filtertotal=$dbfiltot['total'];

			//data
			$q=$sql->run("SELECT * FROM $Table ".$Where.$sSearch.$sCustomFilter.$Orders.$Limit);
			    
			$output = array(
			    "draw" => intval($_POST['draw']),
			    "recordsTotal" => intval($total),
			    "recordsFiltered" => intval($filtertotal),
			    "data" => array()
			);

			$no=1+$_POST['start'];
			foreach($q->fetchAll() as $data){ 
				$aksi="<a class='btn btn-sm btn-primary' target='_blank' href='kuisioner.php?data=".base64_encode($data['id_s'])."&u=".base64_encode($data['ref_idpemohon'])."'>Lihat Kuisioner</a>";
				
				$list=array(
					$no,
					tanggalIndo($data['q_answered'],'j F Y H:i'),
					$data['pke'],
					$aksi
				);
				$output['data'][] = $list;
				$no++;
			}
			echo json_encode($output);
		break;
	}
}