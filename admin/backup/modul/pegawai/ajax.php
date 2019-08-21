<?php
if($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest'){
	die();
}

include ("../../engine/render.php");
if($_POST){
	switch (trim(strip_tags($_POST['a']))) {
		case 'add':
			include ("../../engine/resize-class.php");
			$file_ttd=$_FILES['file_ttd'];
			$ImageName 		= $file_ttd['name'];
			$ImageSize 		= $file_ttd['size'];
			$TempSrc	 	= $file_ttd['tmp_name'];
			$ImageType	 	= $file_ttd['type'];
			$location		= c_BASE."berkas/img/";

			$img_file=get_file_extension($ImageName);
			$ImageFileName=createSlug($_POST['nm_lengkap']);
			$ImageExt=$img_file['file_ext'];
			$saved_img_file=$ImageFileName.".".$ImageExt;

			if($ImageSize>500000){
				echo json_encode(array("stat"=>false,"msg"=>"Ukuran Images Maksimal 500KB."));
				exit();
			}

			if(!isset($ImageName) || !is_uploaded_file($TempSrc)){
				echo json_encode(array("stat"=>false,"msg"=>"Pastikan gambar sudah dipilih"));
				exit();
			}else{
				$img_ttd = new resize($TempSrc);
				$img_ttd -> resizeImage(300, 200, 'auto');
				$img_ttd -> saveImage($location.$saved_img_file, 80);

				$arr_insert=array(
					"idsatker"=>$_POST['satker'],
					"nm_lengkap"=>$_POST['nm_lengkap'],
					"nip"=>$_POST['nip'],
					"jabatan"=>$_POST['jabatan'],
					"no_telp"=>$_POST['no_telp'],
					"email"=>$_POST['email'],
					"ttd"=>$saved_img_file,
					"status"=>2
					);

				$sql->insert('op_pegawai',$arr_insert);
				if($sql->error==null){
					echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil DiSimpan."));
				}else{
					echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
				}
			}
		break;

		case 'update':
			$id=base64_decode($_POST['idp']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_update=array(
				"idsatker"=>$_POST['satker'],
				"nm_lengkap"=>$_POST['nm_lengkap'],
				"nip"=>$_POST['nip'],
				"jabatan"=>$_POST['jabatan'],
				"no_telp"=>$_POST['no_telp'],
				"email"=>$_POST['email']
				);

			if($_POST['gantittd']=='yes'){
				include ("../../engine/resize-class.php");
				$file_ttd=$_FILES['file_ttd'];
				$ImageName 		= $file_ttd['name'];
				$ImageSize 		= $file_ttd['size'];
				$TempSrc	 	= $file_ttd['tmp_name'];
				$ImageType	 	= $file_ttd['type'];
				$location		= c_BASE."berkas/img/";

				$img_file=get_file_extension($ImageName);
				$ImageFileName=createSlug($_POST['nm_lengkap']);
				$ImageExt=$img_file['file_ext'];
				$saved_img_file=$ImageFileName.".".$ImageExt;

				if($ImageSize>500000){
					echo json_encode(array("stat"=>false,"msg"=>"Ukuran Images Maksimal 500KB."));
					exit();
				}

				if(!isset($ImageName) || !is_uploaded_file($TempSrc)){
					echo json_encode(array("stat"=>false,"msg"=>"Pastikan gambar sudah dipilih"));
					exit();
				}else{
					$img_ttd = new resize($TempSrc);
					$img_ttd -> resizeImage(300, 200, 'auto');
					$img_ttd -> saveImage($location.$saved_img_file, 80);

					$arr_update['ttd']=$saved_img_file;
				}
			}

			$sql->update('op_pegawai',$arr_update,array('idp'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil DiSimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}

		break;

		case 'delete':
			$id=base64_decode($_POST['iddt']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			$sql->get_row('op_pegawai',array('idp'=>$id),'ttd');
			$r=$sql->result;
			$ttd_file=$r['ttd'];

			$sql->delete('op_pegawai',array('idp'=>$id));
			if($sql->error==null){
					$location= c_BASE."berkas/img/";
					@unlink($location.$ttd_file);
					echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Dihapus."));
				}else{
					echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
				}
		break;

		case 'dtlist':
			$aColumns = array('p.nm_lengkap','p.jabatan','p.email','rsk.nm_satker');

			//table
			$Table = "op_pegawai p ";
			$Table .= "LEFT JOIN ref_satuan_kerja rsk ON(rsk.id_satker=p.idsatker)";

			//limit
			$Limit = "";
			if ( isset($_POST['start']) && $_POST['length'] != -1 ) {
			    $Limit = " LIMIT ".intval($_POST['start']).", ".intval($_POST['length']);
			}
			//order
			$Orders = " ORDER BY p.nm_lengkap ";

			//cari
			$sSearch = "";
			if ( isset($_POST['cari']) && $_POST['cari']!= '' ) {
			    $str = $_POST['cari'];

			    $sSearch = "WHERE (";
			    for ( $i=0, $ien=count($aColumns) ; $i<$ien ; $i++ ) {
			        $sSearch .= "".$aColumns[$i]." LIKE '%".$str."%' OR ";
			    }
			    $sSearch = substr_replace( $sSearch, "", -3 );
			    $sSearch .= ')';
			}

			$sCustomFilter="";
			if(isset($_POST['filter_satker']) && $_POST['filter_satker']!='all'){
				$satker=$_POST['filter_satker'];
				if($sSearch!=""){
					$sCustomFilter=" AND rsk.id_satker='$satker' ";
				}else{
					$sCustomFilter=" WHERE rsk.id_satker='$satker' ";
				}
			}
			

			$q=$sql->run("SELECT COUNT(p.idp) as total FROM $Table ");
			$rtot=$q->fetch();
			$total=$rtot['total'];

			$q=$sql->run("SELECT COUNT(p.idp) as total FROM $Table ".$sSearch.$sCustomFilter);
			$dbfiltot=$q->fetch();
			$filtertotal=$dbfiltot['total'];

			//data
			$q=$sql->run("SELECT p.*,rsk.nm_satker FROM $Table ".$sSearch.$sCustomFilter.$Orders.$Limit);
			    
			$output = array(
			    "draw" => intval($_POST['draw']),
			    "recordsTotal" => intval($total),
			    "recordsFiltered" => intval($filtertotal),
			    "data" => array()
			);

			$no=1+$_POST['start'];
			foreach($q->fetchAll() as $data){ 
				$edit="<a href='./edit.php?landing=".base64_encode($data['idp'])."'>Edit</a>";
				$del="<a href='#' data-del=\"".base64_encode($data['idp'])."\" class=\"delete-row modal-with-move-anim\">Hapus</a>";
				
				$status=($data['status']=="1"?" &dash; <em class='text-warning'>Tidak Aktif</em>":"");
				$nmlengkap=$data['nm_lengkap']."<br>".$data['nip'].$status."
					<div class=\"actions-hover actions-fade\">".$edit.$del."</div>";

				$users=array(
					$no,
					$nmlengkap,
					$data['jabatan'],
					$data['nm_satker'],
					$data['email']."<br/>".$data['no_telp']
				);
				$output['data'][] = $users;
				$no++;
			}
			echo json_encode($output);
		break;

		case 'default':
			exit();
		break;
	}
}