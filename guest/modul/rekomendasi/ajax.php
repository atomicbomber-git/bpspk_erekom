<?php
if($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest'){
	die();
}

if($_POST){
	include ("../../engine/render.php");
	switch (trim($_POST['a'])) {
		case 'check':
			$kode=$_POST['check_kode'];
			if(!ctype_digit($kode)){
				$c=$sql->run("SELECT kode_surat FROM tb_rekomendasi WHERE no_surat LIKE '%".$kode."%' LIMIT 1");
			}else{
				$c=$sql->run("SELECT kode_surat FROM tb_rekomendasi WHERE kode_surat = '".$kode."' LIMIT 1");
			}
			
			if($c->rowCount()>0){
				$r=$c->fetch();
				$kdsurat=$r['kode_surat'];
				$url=c_URL."modul/rekomendasi/view.php?surat=".$kdsurat."&token=".md5('view'.$kdsurat.'admin');
				$pesan="<p>Data Surat Ditemukan. <a href='".$url."' >Lihat Data</a></p>";
				echo json_encode(array("stat"=>true,"msg"=>$pesan));
			}else{
				$pesan="<p>Data Tidak Ditemukan dari Kode Surat atau No Surat yang telah diinputkan.</p>";
				echo json_encode(array("stat"=>false,"msg"=>$pesan));
			}
		break;

		default:
			echo json_encode(array("stat"=>false,"msg"=>"Mau Ngapain??"));
			exit();
		break;
	}
}
?>