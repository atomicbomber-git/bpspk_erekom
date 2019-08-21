<?php
include ("../../engine/render.php");;
if($_POST){
	switch (trim(strip_tags($_POST['a']))) {
		case 'adddtikan':
			$arr_insert=array(
				'nama_ikan'=>$_POST['nm_ikan'],
				'ref_idkel'=>$_POST['kel'],
				'nama_latin'=>$_POST['nm_latin'],
				'dilindungi'=>$_POST['dilindungi'],
				'ket_dasarhukum'=>$_POST['ket_dasarhukum'],
				'peredaran'=>$_POST['peredaran']
			);

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
				'ref_idkel'=>$_POST['kel'],
				'nama_latin'=>$_POST['nm_latin'],
				'dilindungi'=>$_POST['dilindungi'],
				'ket_dasarhukum'=>$_POST['ket_dasarhukum'],
				'peredaran'=>$_POST['peredaran']
			);

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
				$sql->delete('ref_ciri_ikan',array('id_ikan'=>$id));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		//curd ciri2 ikan
		case 'addcrikan':
			$ikan=base64_decode($_POST['ikan']);
			if(!ctype_digit($ikan)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_insert=array(
				'id_ikan'=>$ikan,
				'id_produk'=>$_POST['produk'],
				'ciri_ciri'=>$_POST['ciri_ciri']);

			$sql->insert('ref_ciri_ikan',$arr_insert);
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Ciri Ikan Berhasil Ditambahkan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'upcrikan':
			$id=base64_decode($_POST['idcr']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$ikan=base64_decode($_POST['ikan']);
			if(!ctype_digit($ikan)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_update=array(
				'id_produk'=>$_POST['produk'],
				'ciri_ciri'=>$_POST['ciri_ciri']);

			$sql->update('ref_ciri_ikan',$arr_update,array('id_ikan'=>$ikan,'id_ciri'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Perubahan data berhasil disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'delcrikan':
			$id=base64_decode($_POST['idcr']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$sql->delete('ref_ciri_ikan',array('id_ciri'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Dihapus."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;
		//-------------------------

		case 'adddtsatker':
			$arr_insert=array(
				'nm_satker'=>$_POST['satker'],
				'kode'=>$_POST['kd_nosurat']
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
				'nm_satker'=>$_POST['satker'],
				'kode'=>$_POST['kd_nosurat']
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


		case 'adduptprl':
			$arr_insert=array(
				'nama'=>$_POST['nama'],
				'email'=>$_POST['email']);

			$sql->insert('ref_upt_prl',$arr_insert);
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Penambahan data berhasil dilakukan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'upuptprl':
			$id=base64_decode($_POST['iddt']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_update=array(
				'nama'=>$_POST['nama'],
				'email'=>$_POST['email']);

			$sql->update('ref_upt_prl',$arr_update,array('id_upt'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Perubahan data berhasil disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'deluptprl':
			$id=base64_decode($_POST['iddt']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			// $sql->delete('ref_upt_prl',array('id_upt'=>$id));
			$sql->update('ref_upt_prl',array('isDelete'=>1),array('id_upt'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Dihapus."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;


		case 'addpsdkp':
			$arr_insert=array(
				'nama'=>$_POST['nama'],
				'email'=>$_POST['email']);

			$sql->insert('ref_psdkp',$arr_insert);
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Penambahan data berhasil dilakukan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'uppsdkp':
			$id=base64_decode($_POST['iddt']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$arr_update=array(
				'nama'=>$_POST['nama'],
				'email'=>$_POST['email']);

			$sql->update('ref_psdkp',$arr_update,array('id_psd'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Perubahan data berhasil disimpan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'delpsdkp':
			$id=base64_decode($_POST['iddt']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			//$sql->delete('ref_psdkp',array('id_upt'=>$id));
			$sql->update('ref_psdkp',array('isDelete'=>1),array('id_psd'=>$id));
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