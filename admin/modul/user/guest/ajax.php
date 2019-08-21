<?php
include ("../../../engine/render.php");

if($_POST){
	switch(trim(strip_tags($_POST['a']))){
		case 'add':
			$user_nicename=$_POST['nama_lengkap'];
			$username=$_POST['username'];
			$user_pass=$_POST['pwd'];
			$user_pass_repeat=$_POST['repeat_pwd'];
			$instansi=$_POST['instansi'];
			$email=$_POST['email'];
			$notelp=$_POST['notelp'];

			if($username==""){
				echo json_encode(array("stat"=>false,"msg"=>"Username Wajib Diisi."));
				exit();
			}

			if($user_pass!=$user_pass_repeat){
				echo json_encode(array("stat"=>false,"msg"=>"Silakan Ulangi Password Yang Telah Diinputkan sebelumnya."));
				exit();
			}

			$data=array(
				"nama_lengkap"=>$user_nicename,
				"username"=>$username,
				"pwd"=>md5(md5($user_pass)),
				"instansi"=>$instansi,
				"email"=>$email,
				"notelp"=>$notelp,
				"status"=>"2",
				"idadm"=>U_ID,
				'isDelete'=>0
			);
			$sql ->insert('tb_guest',$data);
			if($sql->error==null){
				$lastid=$sql->insert_id;
				REG_META_USER($lastid);
				echo json_encode(array("stat"=>true,"msg"=>"User Baru Berhasil Ditambahkan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"User Baru Gagal Ditambahkan."));
			}
		break;

		case 'update':
			$idg=$_POST['i'];
			if(!ctype_digit($idg)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request."));
				exit();
			}
			$user_login=$_POST['username'];
			$user_nicename=$_POST['nama_lengkap'];
			$user_pass=$_POST['pwd'];
			$user_pass_repeat=$_POST['repeat_pwd'];
			$instansi=$_POST['instansi'];
			$email=$_POST['email'];
			$notelp=$_POST['notelp'];
			$user_status=$_POST['user_status'];

			if($user_pass!=$user_pass_repeat){
				echo json_encode(array("stat"=>false,"msg"=>"Silakan Ulangi Password Yang Telah Diinputkan sebelumnya."));
				exit();
			}

			$data_update=array(
				"nama_lengkap"=>$user_nicename,
				"instansi"=>$instansi,
				"email"=>$email,
				"notelp"=>$notelp,
				"status"=>$user_status
			);

			if($user_pass!=""){
				if($user_pass_repeat!=$user_pass){
					echo json_encode(array("stat"=>false,"msg"=>"Silakan Ulangi Password Yang Telah Diinputkan sebelumnya."));
					exit();
				}

				$data_update['pwd']=md5(md5($user_pass));
				$sql->update('web_meta',array('meta_value'=>time()),array('ref_id'=>$idg,'meta_key'=>'U_PWCHANGE','meta_group'=>'4'));
			}

			$update=$sql->update('tb_guest',$data_update,array('idg'=>$idg,'username'=>$user_login));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Update User Berhasil."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Update User Gagal."));
			}
		break;

		case 'delete':
			$idg=$_POST['data'];
			if(!ctype_digit($idg)){
				echo json_encode(array("stat"=>false,"msg"=>"Request Invalid."));
				exit();
			}

			//$sql->delete('tb_guest',array("idg"=>$idg));
			$sql->update('tb_guest',array('isDelete'=>1),array('idg'=>$idg));
			if($sql->error==null){
				//$sql->delete('web_meta',array("ref_id"=>$idg,"meta_group"=>4));
				echo json_encode(array("stat"=>true,"msg"=>"Data User Berhasil Dihapus."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal."));
			}
		break;

		case 'user_check':
			$username=$_POST['username'];
			$sql->get_all('tb_guest',array("username"=>$username),array('idg'));
			$r=$sql->num_rows;
			if($r>0){
				echo 'false';
			}else{
				echo 'true';
			}
		break;

		case 'dtlist':
			//columns for filter
			$aColumns = array('u.nama_lengkap','u.instansi','u.email','u.username');

			//table
			$Table = "tb_guest u";
			$Table .= " LEFT JOIN web_meta m ON (m.ref_id=u.idg AND m.meta_group='4' AND m.meta_key='U_LASTLOG') ";

			//limit
			$Limit = "";
			if ( isset($_POST['start']) && $_POST['length'] != -1 ) {
			    $Limit = " LIMIT ".intval($_POST['start']).", ".intval($_POST['length']);
			}
			//order
			$Orders = " ORDER BY u.nama_lengkap ";

			$where=" WHERE u.isDelete=0 ";
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

			$q=$sql->run("SELECT COUNT(u.idg) as total FROM $Table ");
			$rtot=$q->fetch();
			$total=$rtot['total'];

			$q=$sql->run("SELECT COUNT(u.idg) as total FROM $Table ".$where.$sSearch.$sCustomFilter);
			$dbfiltot=$q->fetch();
			$filtertotal=$dbfiltot['total'];

			//data
			$q=$sql->run("SELECT u.*,m.meta_value as lastlogin FROM $Table ".$where.$sSearch.$sCustomFilter.$Orders.$Limit);
			    
			$output = array(
			    "draw" => intval($_POST['draw']),
			    "recordsTotal" => intval($total),
			    "recordsFiltered" => intval($filtertotal),
			    "data" => array()
			);

			$no=1+$_POST['start'];
			foreach($q->fetchAll() as $data){ 
				
				$edit="<a href='./edit.php?user=".$data['idg']."'>Edit</a>";
				$del="<a href='#' data-del=\"".$data['idg']."\" class=\"delete-row modal-with-move-anim\">Delete</a>";
				
				/*if($data['lvl']!=100){ //utk sementara
					$del="";$edit="";
				}*/
				
				$status=($data['status']=="1"?" &dash; <em class='text-warning'>Tidak Aktif</em>":"");
				$nmlengkap="<a href='#' class='text-bold'>".$data['nama_lengkap']."</a>".$status."
					<div class=\"actions-hover actions-fade\">".$edit.$del."</div>";
				
				if($data['lastlogin']!=""){
					$lastlogin= tanggalIndo(date('Y-m-d H:i:s',$data['lastlogin']),'j F Y H:i');
				}else{
					$lastlogin="Belum Pernah Login";
				}
				$email=(($data['email']!="")?"Email : ".$data['email']:"");
				$telp=(($data['notelp']!="")?"Telp : ".$data['notelp']:"");
				$br=($email!=""?"<br/>":"");

				$users=array(
					$no,
					$nmlengkap,
					$data['username'],
					$data['instansi'],
					$email.$br.$telp,
					$lastlogin
				);
				$output['data'][] = $users;
				$no++;
			}
			echo json_encode($output);
		break;	

		default:
			echo json_encode(array("stat"=>false,"msg"=>"Invalid Request"));
		break;
	}
}

function REG_META_USER($UID){
    $sql=new dbPDO;
    $meta=array(
        "U_VERIFY"=>"1",
        "U_NOWLOG"=>"",
        "U_TOTAL_LOGIN"=>"0",
        "U_PWCHANGE"=>"",
        "U_REGISTERED"=>time(),
        "U_LASTLOG"=>"",
        "U_IP"=>"",
        "U_OPTIONS"=>""
    );

    $option_autoload=array("U_VERIFY","U_NOWLOG","U_LASTLOG","U_IP");
    foreach($meta as $key => $value){
        if(in_array($key,  $option_autoload)){
            $option="autoload";
        }else{
            $option="";
        }
         $data=array(
            "ref_id"=>$UID,
            "meta_key"=>$key,
            "meta_value"=>$value,
            "meta_option"=>$option,
            "meta_group"=>"4"
        );
        $sql->insert('web_meta',$data);
    }
}
