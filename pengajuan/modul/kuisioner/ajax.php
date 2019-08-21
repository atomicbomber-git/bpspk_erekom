<?php
if($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest'){
	die();
}

if($_POST){
	include ("../../engine/render.php");
	switch (trim(strip_tags($_POST['a']))) {
		case 'kuisioner':
			$sql->order_by=" q_answered DESC ";
			$sql->limit=" 1";
			$sql->get_row('tb_kuisioner_s',array('ref_idpemohon'=>U_ID));
			if($sql->num_rows>0){
				$r=$sql->result;
				$lastkuisionerdate=$r['q_answered'];

				$q=$sql->run("SELECT COUNT(idp) jlh FROM tb_permohonan WHERE ref_iduser='".U_ID."' AND tgl_pengajuan> '".$lastkuisionerdate."'");
				$r=$q->fetch();
				if(($r['jlh']+1)<5){
					echo json_encode(array("stat"=>false,"msg"=>"Kuisioner Sudah Diisi. Silakan Refresh Ulang Halaman Ini."));
					die();
				}
			}

			$jlh_permohonan=$sql->get_count('tb_permohonan',array('ref_iduser'=>U_ID));
			$tgl_sekarang=date('Y-m-d H:i:s');
			$arr_insert2=array(
				"ref_idpemohon"=>U_ID,
				"pke"=>($jlh_permohonan+1),
				"tahun"=>date('Y'),
				"q_answered"=>$tgl_sekarang);

			$sql->insert('tb_kuisioner_s',$arr_insert2);
			$id_s=$sql->insert_id;
			if($sql->error==null){
				$sql->get_all('tb_kuisioner_q',array('stat'=>1));
				if($sql->num_rows>0){
					$n=1;
					$sjns=0;
					foreach ($sql->result as $s) {
						if($sjns!=$s['id_jns']){
							$sjns=$s['id_jns'];
							$n=1;
						}
						if($sjns==1){
							$arr_insert=array(
								"ref_idq"=>$s['id_q'],
								"ref_idpemohon"=>U_ID,
								"ref_idstat"=>$id_s,
								"jawaban"=>$_POST["soalkl_".$s['id_q']."_".$sjns."_".$n],
								"harapan"=>$_POST["soalhk_".$s['id_q']."_".$sjns."_".$n],
								"date_a"=>$tgl_sekarang);

							$sql->insert('tb_kuisioner_a',$arr_insert);
						}else{
							$arr_insert=array(
								"ref_idq"=>$s['id_q'],
								"ref_idpemohon"=>U_ID,
								"ref_idstat"=>$id_s,
								"jawaban"=>$_POST["soal_".$s['id_q']."_".$sjns."_".$n],
								"harapan"=>0,
								"date_a"=>$tgl_sekarang);

							$sql->insert('tb_kuisioner_a',$arr_insert);
						}
					$n++;
					}
				}
				echo json_encode(array("stat"=>true,"msg"=>"Terima Kasih Telah Mengisi Kuisioner"));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal"));
			}
			
		break;
		default:
			echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
		break;
	}
}