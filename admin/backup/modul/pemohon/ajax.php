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
			$Table .= " JOIN tb_biodata b ON(b.ref_iduser=u.iduser) ";
			$Table .= " JOIN web_meta reg ON(reg.meta_key='U_REGISTERED' AND reg.meta_group='1' AND reg.ref_id=u.iduser) ";

			//limit
			$Limit = "";
			if ( isset($_POST['start']) && $_POST['length'] != -1 ) {
			    $Limit = " LIMIT ".intval($_POST['start']).", ".intval($_POST['length']);
			}
			//order
			$Orders = " ORDER BY u.iduser DESC ";

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
			$q=$sql->run("SELECT u.iduser,u.nama_lengkap,u.email,b.*,reg.meta_value as tgl_daftar FROM $Table ".$Where.$sSearch.$sCustomFilter.$Orders.$Limit);
			    
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
				$sql->get_row('tb_permohonan',array('ref_iduser'=>$data['iduser']),'tgl_pengajuan');
				$sql->order_by="tgl_pengajuan DESC";
				if($sql->num_rows>0){
					$ph=$sql->result;
					$pengajuanterakhir=tanggalIndo($ph['tgl_pengajuan'],'j F Y H:i');
				}else{
					$pengajuanterakhir="Belum Pernah Mengajukan Permohonan";
				}
				//$pengajuanterakhir="Belum Pernah Mengajukan Permohonan";
				$users=array(
					$no,
					$data['nama_lengkap'],
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

			$q=$sql->run("SELECT COUNT(p.idp) as total FROM $Table ");
			$rtot=$q->fetch();
			$total=$rtot['total'];

			$q=$sql->run("SELECT COUNT(p.idp) as total FROM $Table ".$Where.$sSearch.$sCustomFilter);
			$dbfiltot=$q->fetch();
			$filtertotal=$dbfiltot['total'];

			//data
			$q=$sql->run("SELECT c.nama_lengkap,p.tujuan,p.penerima,p.tgl_pengajuan,p.idp,p.status,p.ref_iduser FROM $Table ".$Where.$sSearch.$sCustomFilter.$Orders.$Limit);
			    
			$output = array(
			    "draw" => intval($_POST['draw']),
			    "recordsTotal" => intval($total),
			    "recordsFiltered" => intval($filtertotal),
			    "data" => array()
			);

			$no=1+$_POST['start'];
			$arr_status=array(
				1=>"Pemeriksaan Data.",
				2=>"Data Diterima, Pengajuan Sedang Diproses Oleh Admin.",
				3=>"Data Ditolak, Berkas/Data Tidak Lengkap.",
				4=>"Pemeriksaan Sampel Telah Dilakukan.",
				5=>"Surat Rekomendasi Sudah Diterbitkan."
			);

			foreach($q->fetchAll() as $data){ 
				$aksi="<a class='btn btn-sm btn-primary' href='./detail-pengajuan.php?data=".base64_encode($data['idp'])."&u=".base64_encode($data['ref_iduser'])."'>Detail Data</a>";
				
				$users=array(
					$no,
					$data['penerima']."<br/>".$data['tujuan'],
					tanggalIndo($data['tgl_pengajuan'],'j F Y H:i'),
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
	}
}