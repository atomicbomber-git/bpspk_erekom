<?php
include ("../../engine/render.php");;
if($_POST){
	switch (trim(strip_tags($_POST['a']))) {
		case 'add_q':
			$pertanyaan=$_POST['pertanyaan'];
			if($pertanyaan==""){
				echo json_encode(array("stat"=>false,"msg"=>"Pertanyaan harus diisi."));
				exit();
			}
			$arr_insert=array(
				"pertanyaan"=>$pertanyaan,
				"stat"=>1,
				"date_q"=>date('Y-m-d H:i:s'));
			$sql->insert('tb_kuisioner_q',$arr_insert);
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Pertanyaan Berhasil Ditambahkan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
		break;

		case 'update_q':
			$id=base64_decode($_POST['idq']);
			$pertanyaan=$_POST['pertanyaan'];
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}
			
			if($pertanyaan==""){
				echo json_encode(array("stat"=>false,"msg"=>"Pertanyaan harus diisi."));
				exit();
			}
			$arr_update=array(
				"pertanyaan"=>$pertanyaan);
			$sql->update('tb_kuisioner_q',$arr_update,array("id_q"=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Pertanyaan Berhasil Diubah."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}	
		break;

		case 'del_q':
			$id=base64_decode($_POST['idq']);
			if(!ctype_digit($id)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
				exit();
			}

			$sql->delete('tb_kuisioner_q',array('id_q'=>$id));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Pertanyaan Berhasil Dihapus."));
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