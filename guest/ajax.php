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
				//ditutup sementara
				header('Content-Type: application/json');
				echo json_encode(array("stat"=>false,"msg"=>"Maaf, Halaman ini tidak dapat diakses untuk sementara waktu.."));
				exit();

				$sandiuser = md5(md5(trim($_POST['p_pwd'])));
				$authuser = preg_replace("\sOR\s|\=|\#", "",$_POST['p_uname']);

				$sql -> get_row( 'tb_guest', array( 'username'=>$authuser), array('idg','pwd'));

				if($sql->num_rows==0){
					header('Content-Type: application/json');
					echo json_encode(array("stat"=>false,"msg"=>"Akun Tidak Ditemukan."));
					exit();
				}else{
					$row = $sql->result;
					$db_pwd=$row['pwd'];
					if($db_pwd==$sandiuser){
						$autolog = $_POST['autologin'];
						$cookiepass = md5(trim($_POST['p_pwd']));
						$cookieval = $row['idg'].".".$cookiepass;
						
						$datalogin=array(
							'uid'=>$row['idg'],
							'date_login'=>date('y-m-d H:i:s'),
							'user_ip'=>getIP(), 
							'user_agent'=>$_SERVER['HTTP_USER_AGENT']
							);

						$sql -> insert('users_stat_login',$datalogin);
						$sql -> run("UPDATE web_meta SET meta_value=meta_value+1 WHERE ref_id='".$row['idg']."' AND meta_key='U_TOTAL_LOGIN' AND meta_group='4'");

						if($SITE_CONF_AUTOLOAD['tracking'] == "session"){
							$_SESSION[$SITE_CONF_AUTOLOAD['cookie']] = $cookieval;
						}else{
							if($autolog == 1){
								isicookie($SITE_CONF_AUTOLOAD['cookie'], $cookieval, ( time()+3600*24*7)); //7 hari	
							}else{
								isicookie($SITE_CONF_AUTOLOAD['cookie'], $cookieval, ( time()+3600*24)); //1 hari
							}
						}

						define("USER", TRUE);
						echo json_encode(array("stat"=>true,"msg"=>"Login Berhasil"));
					}else{
						header('Content-Type: application/json');
						echo json_encode(array("stat"=>false,"msg"=>"Login Gagal, Pastikan Anda Mengisi Password yang sesuai."));
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