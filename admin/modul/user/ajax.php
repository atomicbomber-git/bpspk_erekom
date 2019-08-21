<?php
include ("../../engine/render.php");

if($_POST){
	include ("function.php");
	$allowed_admin=array('1','8');
	switch(trim(strip_tags($_POST['a']))){
		case 'up':
			$namalengkap=$_POST['nama_lengkap'];
			$email=$_POST['email'];
			$jabatan=$_POST['jabatan'];
			$no_telp=$_POST['no_telp'];
			$newpwd=$_POST['newpwd'];
			$newpwd_repeat=$_POST['newpwd_repeat'];
			$oldpwd=$_POST['oldpwd'];

			if($newpwd!=$newpwd_repeat){
				echo json_encode(array("stat"=>false,"msg"=>"Password Yang Anda masukkan tidak sama dengan sebelumnya."));
				exit();
			}

			if($newpwd!=""){
				if($oldpwd==""){
					echo json_encode(array("stat"=>false,"msg"=>"Silakan Masukkan Password Lama Anda."));
					exit();
				}else{
					$sql->get_row('op_user',array('idu'=>U_ID),array('pwd'));
					$check=$sql->result;
					$dboldpwd=$check['pwd'];
					if(md5(md5($oldpwd))!=$dboldpwd){
						echo json_encode(array("stat"=>false,"msg"=>"Password Lama Yang Anda Masukkan Salah, Harap Masukkan Password yang Benar."));
						exit();
					}
				}
				$newpassword=" ,pwd='".md5(md5($newpwd))."' ";
				$sql ->update('web_meta',array('meta_value'=>time()),array('ref_id'=>U_ID,'meta_key'=>'U_PWCHANGE','meta_group'=>2));
			}else{
				$newpassword="";
			}

			$update=$sql->run("UPDATE op_user SET nm_lengkap='".$namalengkap."', email='".$email."', jabatan='".$jabatan."', no_telp='".$no_telp."' $newpassword WHERE idu='".U_ID."'");
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Profil Berhasil DiUpdate"));
				exit();
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Gagal Update Profil, Error: DB"));
				exit();
			}
		break;

		case 'add':
			$user_login=$_POST['username'];
			$user_nicename=$_POST['nama_lengkap'];
			$user_email=$_POST['email'];
			$user_pass=$_POST['pwd'];
			$user_pass_repeat=$_POST['repeat_pwd'];
			$jabatan=$_POST['jabatan'];
			$no_telp=$_POST['no_telp'];
			$user_level=$_POST['hak_akses'];
			$id_peg=0;

			if($user_login==""){
				echo json_encode(array("stat"=>false,"msg"=>"Username Wajib Diisi."));
				exit();
			}

			if($user_pass!=$user_pass_repeat){
				echo json_encode(array("stat"=>false,"msg"=>"Silakan Ulangi Password Yang Telah Diinputkan sebelumnya."));
				exit();
			}

			if($user_level==90 OR $user_level==91 OR $user_level==95){
				$nip=$_POST['pegawai'];
				$sql->get_row('op_pegawai',array('nip'=>$nip,'status'=>2),array('idp','nm_lengkap','email','no_telp','jabatan'));
				$u=$sql->result;

				$user_nicename=$u['nm_lengkap'];
				$user_email=$u['email'];
				$jabatan=$u['jabatan'];
				$no_telp=$u['no_telp'];
				$id_peg=$u['idp'];
			}

			$data=array(
				"username"=>$user_login,
				"ref_idpeg"=>$id_peg,
				"nm_lengkap"=>$user_nicename,
				"email"=>$user_email,
				"pwd"=>md5(md5($user_pass)),
				"lvl"=>$user_level,
				"jabatan"=>$jabatan,
				"no_telp"=>$no_telp,
				"status"=>"2",
				"vk"=>""
			);
			$sql ->insert('op_user',$data);
			if($sql->error==null){
				$lastid=$sql->insert_id;
				REG_META_USER($lastid);
				echo json_encode(array("stat"=>true,"msg"=>"User Baru Berhasil Ditambahkan."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"User Baru Gagal Ditambahkan."));
			}
		break;

		case 'update':
			if(!in_array(U_ID,$allowed_admin)){
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Tidak Diizinkan."));
				exit();
			}
			
			$idu=$_POST['i'];
			if(!ctype_digit($idu)){
				echo json_encode(array("stat"=>false,"msg"=>"Invalid Request."));
				exit();
			}
			$user_login=$_POST['username'];
			$user_nicename=$_POST['nama_lengkap'];
			$user_email=$_POST['email'];
			$user_pass=$_POST['pwd'];
			$user_pass_repeat=$_POST['repeat_pwd'];
			$jabatan=$_POST['jabatan'];
			$no_telp=$_POST['no_telp'];
			$user_level=$_POST['hak_akses'];
			$user_status=$_POST['user_status'];

			if($user_login==""){
				echo json_encode(array("stat"=>false,"msg"=>"Username Wajib Diisi."));
				exit();
			}

			if($user_pass!=$user_pass_repeat){
				echo json_encode(array("stat"=>false,"msg"=>"Silakan Ulangi Password Yang Telah Diinputkan sebelumnya."));
				exit();
			}

			$data_update=array(
				"nm_lengkap"=>$user_nicename,
				"email"=>$user_email,
				"lvl"=>$user_level,
				"jabatan"=>$jabatan,
				"no_telp"=>$no_telp,
				"status"=>$user_status
			);

			if($user_pass!=""){
				if($user_pass_repeat!=$user_pass){
					echo json_encode(array("stat"=>false,"msg"=>"Silakan Ulangi Password Yang Telah Diinputkan sebelumnya."));
					exit();
				}

				$data_update['pwd']=md5(md5($user_pass));
				$sql->update('web_meta',array('meta_value'=>time()),array('ref_id'=>$idu,'meta_key'=>'U_PWCHANGE','meta_group'=>'2'));
			}

			$update=$sql->update('op_user',$data_update,array('idu'=>$idu));
			if($sql->error==null){
				echo json_encode(array("stat"=>true,"msg"=>"Update User Berhasil."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Update User Gagal."));
			}
		break;

		case 'delete':
			if(!in_array(U_ID,$allowed_admin)){
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Tidak Diizinkan."));
				exit();
			}
			$idu=$_POST['data'];
			if(!ctype_digit($idu)){
				echo json_encode(array("stat"=>false,"msg"=>"Request Invalid."));
				exit();
			}

			$sql->delete('op_user',array("idu"=>$idu));
			if($sql->error==null){
				$sql->delete('web_meta',array("ref_id"=>$idu,"meta_group"=>2));
				echo json_encode(array("stat"=>true,"msg"=>"Data User Berhasil Dihapus."));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Aksi Gagal."));
			}
		break;

		case 'user_check':
			$username=$_POST['username'];
			$sql->get_all('op_user',array("username"=>$username),array('idu'));
			$r=$sql->num_rows;
			if($r>0){
				echo 'false';
			}else{
				echo 'true';
			}
		break;

		case 'resetnav':
			NAV_LEVEL();
		break;

		case 'dtlist':
			//columns for filter
			$aColumns = array('u.nm_lengkap','u.jabatan','u.email','u.username');

			//table
			$Table = "op_user u";
			$Table .= " LEFT JOIN web_meta m ON (m.ref_id=u.idu AND m.meta_group='2' AND m.meta_key='U_LASTLOG') ";

			//limit
			$Limit = "";
			if ( isset($_POST['start']) && $_POST['length'] != -1 ) {
			    $Limit = " LIMIT ".intval($_POST['start']).", ".intval($_POST['length']);
			}
			//order
			$Orders = " ORDER BY u.nm_lengkap ";

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
			if(isset($_POST['filterlvl']) && $_POST['filterlvl']!='all'){
				$lvl=$_POST['filterlvl'];
				if($sSearch!=""){
					$sCustomFilter=" AND u.lvl='$lvl' ";
				}else{
					$sCustomFilter=" WHERE u.lvl='$lvl' ";
				}
			}

			$q=$sql->run("SELECT COUNT(u.idu) as total FROM $Table ");
			$rtot=$q->fetch();
			$total=$rtot['total'];

			$q=$sql->run("SELECT COUNT(u.idu) as total FROM $Table ".$sSearch.$sCustomFilter);
			$dbfiltot=$q->fetch();
			$filtertotal=$dbfiltot['total'];
			//$filtertotal=$total;

			//data
			$q=$sql->run("SELECT u.*,m.meta_value as lastlogin FROM $Table ".$sSearch.$sCustomFilter.$Orders.$Limit);
			    
			$output = array(
			    "draw" => intval($_POST['draw']),
			    "recordsTotal" => intval($total),
			    "recordsFiltered" => intval($filtertotal),
			    "data" => array()
			);
			$no=1+$_POST['start'];
			foreach($q->fetchAll() as $data){ 
				if($data['idu']==U_ID){
					$edit="<a href='".c_MODULE."user/my-profile.php'>Edit</a>";
					$del="";
				}else{
					$edit="<a href='./edit.php?landing=".$data['idu']."'>Edit</a>";
					$del="<a href='#' data-del=\"".$data['idu']."\" class=\"delete-row modal-with-move-anim\">Delete</a>";
				}

				/*if($data['lvl']!=100){ //utk sementara
					$del="";$edit="";
				}*/
				
				$status=($data['status']=="1"?" &dash; <em class='text-warning'>Tidak Aktif</em>":"");
				$nmlengkap="<a href='#' class='text-bold'>".$data['nm_lengkap']."</a>".$status."
					<div class=\"actions-hover actions-fade\">".$edit.$del."</div>";
				
				if($data['lastlogin']!=""){
					$lastlogin= tanggalIndo(date('Y-m-d H:i:s',$data['lastlogin']),'j F Y H:i');
				}else{
					$lastlogin="Belum Pernah Login";
				}

				$users=array(
					$no,
					$nmlengkap,
					$data['username']."<br><small>".get_Level($data['lvl'])."</small>",
					$data['email'],
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

?>