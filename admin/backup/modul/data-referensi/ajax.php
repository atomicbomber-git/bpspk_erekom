<?php
include ("../../engine/render.php");;
if($_POST){
	switch (trim(strip_tags($_POST['a']))) {
		case 'adddtikan':
			$arr_insert=array(
				'nama_ikan'=>$_POST['nm_ikan'],
				'nama_latin'=>$_POST['nm_latin']);

			$sql->insert('ref_data_ikan',$arr_insert);
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Penambahan data berhasil dilakukan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'updtikan':
			$id=base64_decode($_POST['idik']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_update=array(
				'nama_ikan'=>$_POST['nm_ikan'],
				'nama_latin'=>$_POST['nm_latin']);

			$sql->update('ref_data_ikan',$arr_update,array('id_ikan'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Perubahan data berhasil disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'deldtikan':
			$id=base64_decode($_POST['iddt']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$sql->delete('ref_data_ikan',array('id_ikan'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Dihapus."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'adddtsatker':
			$arr_insert=array(
				'nm_satker'=>$_POST['satker']
			);
			$sql->insert('ref_satuan_kerja',$arr_insert);
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Penambahan data berhasil dilakukan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'updtsatker':
			$id=base64_decode($_POST['idsat']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_update=array(
				'nm_satker'=>$_POST['satker']
				);

			$sql->update('ref_satuan_kerja',$arr_update,array('id_satker'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Perubahan data berhasil disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'deldtsatker':
			$id=base64_decode($_POST['iddt']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$sql->delete('ref_satuan_kerja',array('id_satker'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Dihapus."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'adddtbk':
			$arr_insert=array(
				'nama'=>$_POST['nama'],
				'email'=>$_POST['email']);

			$sql->insert('ref_balai_karantina',$arr_insert);
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Penambahan data berhasil dilakukan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'updtbk':
			$id=base64_decode($_POST['idbk']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_update=array(
				'nama'=>$_POST['nama'],
				'email'=>$_POST['email']);

			$sql->update('ref_balai_karantina',$arr_update,array('idbk'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Perubahan data berhasil disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'deldtbk':
			$id=base64_decode($_POST['iddt']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$sql->delete('ref_balai_karantina',array('idbk'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Dihapus."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'adddtjproduk':
			$arr_insert=array(
				'jenis_sampel'=>$_POST['nm_jenis']);

			$sql->insert('ref_jns_sampel',$arr_insert);
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Penambahan data berhasil dilakukan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'updtjproduk':
			$id=base64_decode($_POST['idjp']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_update=array(
				'jenis_sampel'=>$_POST['nm_jenis']);
			
			$sql->update('ref_jns_sampel',$arr_update,array('id_ref'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Perubahan data berhasil disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'deldtjproduk':
			$id=base64_decode($_POST['iddt']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$sql->delete('ref_jns_sampel',array('id_ref'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Dihapus."));
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
?>