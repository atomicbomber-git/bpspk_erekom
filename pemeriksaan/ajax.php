<?php
if($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest'){
	die();
}

if($_POST){
	include ("engine/render.php");
	switch (trim($_POST['a'])) {
		case 'unlock':	
			$unlock_pwd=$_POST['pwd'];
			if(md5(md5(trim($unlock_pwd)))==U_PASS){
				if($SITE_CONF_AUTOLOAD['tracking'] == "session"){
					$_SESSION['lockscreen']="";
				}else{
					isicookie("lockscreen", "", (time()-3600*24*30));
				}
				echo json_encode(array("stat"=>true,"msg"=>"Yey.. "));
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Password Anda Tidak Sesuai"));
			}
		break;

		case 'l': //action utk login
			// include ("engine/recaptchalib.php");
			// $secret_key="6LdTkiYTAAAAAIYqejXL05NKbq43RPfehku5GWh4";
			// $reCaptcha = new ReCaptcha($secret_key);

			// if($_POST["g-recaptcha-response"]==""){
			// 	header('Content-Type: application/json');
			// 	echo json_encode(array("stat"=>false,"msg"=>"Silakan Verifikasi Captcha Terlebih Dahulu Sebelum Submit"));
			// 	exit();
			// }

			//$response = $reCaptcha->verifyResponse(getIP(),$_POST["g-recaptcha-response"]);
			//if ($response != null && $response->success) {
			if (TRUE) {

				$sandiuser = $_POST['p_pwd'];
				$uname = $_POST['p_uname'];
				$email=$_POST['p_email'];

				$sql -> get_row( 'tb_permohonan', array( 'log_u'=>$uname,'log_p'=>$sandiuser), array('idp','status'));

				if($sql->num_rows==0){
					header('Content-Type: application/json');
					echo json_encode(array("stat"=>false,"msg"=>"Akun Tidak Ditemukan."));
					exit();
				}else{
					$row = $sql->result;
					$idp=$row['idp'];
					if($row['status']==5){
						header('Content-Type: application/json');
						echo json_encode(array("stat"=>false,"msg"=>"Permohonan Sudah Disahkan."));
						exit();
					}

					if($row['status']==1 OR $row['status']==3){
						header('Content-Type: application/json');
						echo json_encode(array("stat"=>false,"msg"=>"Akun Tidak Ditemukan."));
						exit();
					}
					$c=$sql->run("SELECT op.nip,op.nm_lengkap FROM tb_petugas_lap pl JOIN op_pegawai op ON(pl.ref_idpeg=op.idp) WHERE pl.ref_idp='$idp' AND op.email='$email' LIMIT 1");
					if($c->rowCount()>0){
						$cookieval = $row['idp'].".".$uname.".".md5($email);
						
						if($SITE_CONF_AUTOLOAD['tracking'] == "session"){
							$_SESSION[$SITE_CONF_AUTOLOAD['cookie']] = $cookieval;
						}else{
							if($autolog == 1){
								isicookie($SITE_CONF_AUTOLOAD['cookie'], $cookieval, ( time()+3600*24*7)); //7 hari	
							}else{
								isicookie($SITE_CONF_AUTOLOAD['cookie'], $cookieval, ( time()+3600*24)); //1 hari
							}
						}

						// echo $cookieval;
						define("USER", TRUE);
						echo json_encode(array("stat"=>true,"msg"=>"Login Berhasil"));
					}else{
						header('Content-Type: application/json');
						echo json_encode(array("stat"=>false,"msg"=>"Email Petugas Tidak terdaftar."));
						exit();
					}
				}

			}else{
				header('Content-Type: application/json');
				echo json_encode(array("alert"=>'false',"msg"=>"Verifikasi Google Captcha gagal."));
			}
		break;

		default:
			echo json_encode(array("stat"=>false,"msg"=>"Mau Ngapain??"));
			exit();
		break;
	}
}
?>