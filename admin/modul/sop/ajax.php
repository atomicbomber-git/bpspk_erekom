<?php
include ("../../engine/render.php");;
if($_POST){
	switch (trim(strip_tags($_POST['a']))) {
		case 'update_m':
			$isi=$_POST['isi'];
			$arr_update=array(
				"isi_maklumat"=>$isi,
				"updated_at"=>date('Y-m-d H:i:s'));

			$sql->update('tb_maklumat',$arr_update,array('id'=>1));
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