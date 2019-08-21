<?php
include ("../../../engine/render.php");;
if($_POST){
	switch (trim(strip_tags($_POST['a']))) {
		case 'up_st':
			$isi=$_POST['isi'];
			$arr_update=array(
				"bag1"=>$isi,
				"updated_at"=>date('Y-m-d H:i:s'));

			$sql->update('ref_redaksi_surat',$arr_update,array('id'=>1));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Data Berhasil Disimpan."));
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