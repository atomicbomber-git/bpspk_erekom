<?php
if($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest'){
	die();
}

include_once("../bootstrap.php");

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
			/*include ("engine/recaptchalib.php");
			$secret_key="6LdTkiYTAAAAAIYqejXL05NKbq43RPfehku5GWh4";
			$reCaptcha = new ReCaptcha($secret_key);

			if($_POST["g-recaptcha-response"]==""){
				header('Content-Type: application/json');
				echo json_encode(array("stat"=>false,"msg"=>"Silakan Verifikasi Captcha Terlebih Dahulu Sebelum Submit"));
				exit();
			}*/

			//$response = $reCaptcha->verifyResponse(getIP(),$_POST["g-recaptcha-response"]);
			//if ($response != null && $response->success) {

				$sandiuser = md5(md5(trim($_POST['authsandi'])));
                $authemail = $_POST['authemail'];
                

				$sql -> get_row( 'tb_userpublic', array( 'email'=>$authemail), array('iduser','pwd'));

				if($sql->num_rows==0){
					header('Content-Type: application/json');
					echo json_encode(array("stat"=>false,"msg"=>"Akun Tidak Ditemukan."));
					exit();
				}else{
					$row = $sql->result;
					$db_pwd=$row['pwd'];
					if($db_pwd==$sandiuser){
						$autolog = $_POST['autologin'];
						$cookiepass = md5(trim($_POST['authsandi']));
						$cookieval = $row['iduser'].".".$cookiepass;
						
						$datalogin=array(
							'uid'=>$row['iduser'],
							'date_login'=>date('y-m-d H:i:s'),
							'user_ip'=>getIP(), 
							'user_agent'=>$_SERVER['HTTP_USER_AGENT']
							);

						$sql -> insert('users_stat_login',$datalogin);
						$sql -> run("UPDATE web_meta SET meta_value=meta_value+1 WHERE ref_id='".$row['iduser']."' AND meta_key='U_TOTAL_LOGIN' AND meta_group='1' ");

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

			//}else{
			//	header('Content-Type: application/json');
			//	echo json_encode(array("alert"=>'false',"msg"=>"Verifikasi Google Captcha gagal."));
			//}
		break;

		case 'r': //action utk registrasi
			include ("engine/recaptchalib.php");
			$secret_key="6LdTkiYTAAAAAIYqejXL05NKbq43RPfehku5GWh4";
			$reCaptcha = new ReCaptcha($secret_key);

			$nama_lengkap=$_POST['nama_lengkap'];
			$email=$_POST['email'];
			$pwd=$_POST['pwd'];
			$pwd_confirm=$_POST['pwd_confirm'];
			if($_POST["g-recaptcha-response"]==""){
				header('Content-Type: application/json');
				echo json_encode(array("stat"=>false,"msg"=>"Silakan Verifikasi Captcha Terlebih Dahulu Sebelum Submit"));
				exit();
			}

			if($nama_lengkap==""){
				header('Content-Type: application/json');
				echo json_encode(array("stat"=>false,"msg"=>"Nama Lengkap Harus Diisi."));
				exit();
			}

			if($email==""){
				header('Content-Type: application/json');
				echo json_encode(array("stat"=>false,"msg"=>"Email Harus Diisi."));
				exit();
			}else{
				$check=$sql->get_count('tb_userpublic',array('email'=>$email));
				if($check>0){
					header('Content-Type: application/json');
					echo json_encode(array("stat"=>false,"msg"=>"Email Yang Anda Pakai Sudah Digunakan,."));
					exit();
				}
			}

			if($pwd==""){
				header('Content-Type: application/json');
				echo json_encode(array("stat"=>false,"msg"=>"Password Harus Diisi."));
				exit();
			}

			$response = $reCaptcha->verifyResponse(getIP(),$_POST["g-recaptcha-response"]);
			if ($response != null && $response->success) {

				$ver_code=strtoupper(substr(md5(time()),0,5));

				$arr_insert=array(
					"nama_lengkap"=>$nama_lengkap,
					"email"=>$email,
					"pwd"=>md5(md5($pwd)),
					"verifikasi"=>$ver_code,
					"status"=>1);
				$sql->insert('tb_userpublic',$arr_insert);
				if($sql->error==null){
					$last_id=$sql->insert_id;
					$reg_time=date('Y-m-d H:i:s');

					$sql->run("INSERT INTO `web_meta` 
					(`mid`, `ref_id`, `meta_key`, `meta_value`, `meta_option`, `meta_group`) 
					VALUES 
					(NULL, '".$last_id."', 'U_VERIFY', '0', 'autoload', '1'), 
					(NULL, '".$last_id."', 'U_NOWLOG', '', 'autoload', '1'), 
					(NULL, '".$last_id."', 'U_TOTAL_LOGIN', '0', NULL, '1'), 
					(NULL, '".$last_id."', 'U_PWCHANGE', NULL, NULL, '1'), 
					(NULL, '".$last_id."', 'U_REGISTERED', ".time().", NULL, '1'), 
					(NULL, '".$last_id."', 'U_LASTLOG', NULL, 'autoload', '1'), 
					(NULL, '".$last_id."', 'U_IP', NULL, 'autoload', '1'), 
					(NULL, '".$last_id."', 'U_OPTIONS', NULL, NULL, '1')");

					require '../assets/phpmailer/PHPMailerAutoload.php';
					$isi="<p>Hi, ".$nama_lengkap.", Terima Kasih Telah Mendaftar Pada Aplikasi E-Rekomendasi BPSPL Pontianak</p><p>Kode Verifikasi Untuk Akun Anda Adalah : <br><h3><strong>".$ver_code."</strong></h3></p>";
					$arr=array(
						"send_to"=>$email,
						"send_to_name"=>$nama_lengkap,
						"subject_email"=>"Verifikasi Akun - BPSPL Pontianak",
						"isi_email"=>$isi);
					sendMail($arr);

					header('Content-Type: application/json');
					echo json_encode(array("stat"=>true,"msg"=>"Registrasi Anda Berhasil, Anda dapat login untuk menggunakan aplikasi ini."));
					exit();
				}
			}else{
				header('Content-Type: application/json');
				echo json_encode(array("alert"=>'false',"msg"=>"Verifikasi Google Captcha gagal."));
			}
		break;

		case 'ec': //check email
			$email=$_POST['email'];
			$sql->get_row('tb_userpublic',array("email"=>$email),array('iduser'));
			$r=$sql->num_rows;
			if($r>0){
				echo 'false';
			}else{
				echo 'true';
			}
		break;
		
		case 'vk': //verifikasi kode
			$verkode=trim(strip_tags($_POST['ver_kode']));
			$sql->get_row('tb_userpublic',array('iduser'=>U_ID),'verifikasi');
			$row=$sql->result;
			$db_verkode=$row['verifikasi'];
			if($verkode==$db_verkode){
				$sql->update('web_meta',array('meta_value'=>1),array('ref_id'=>U_ID,'meta_key'=>'U_VERIFY','meta_group'=>1));
				if($sql->error==null){
					header('Content-Type: application/json');
					echo json_encode(array("stat"=>true,"msg"=>"Done, Verified"));
					exit();
				}
			}else{
				header('Content-Type: application/json');
				echo json_encode(array("stat"=>false,"msg"=>"Kode Verifikasi Tidak Cocok."));
				exit();
			}
		break;

		case 'svk': //kirim ulang kode verifikasi ke email pengguna
			$ver_code=strtoupper(substr(md5(time()),0,5));
			$sql->update('tb_userpublic',array('verifikasi'=>$ver_code),array('iduser'=>U_ID));
			require '../assets/phpmailer/PHPMailerAutoload.php';

			$isi="<p>Hi, ".U_NAME.", Berikut Adalah Kode Verifikasi Untuk Akun Anda Adalah : </p><h4>".$ver_code."</h4>";
			$arr=array(
				"send_to"=>U_EMAIL,
				"send_to_name"=>U_NAME,
				"subject_email"=>"Verifikasi Akun - BPSPL Pontianak",
				"isi_email"=>$isi);

			if(sendMail($arr)){
				header('Content-Type: application/json');
				echo json_encode(array("stat"=>true,"msg"=>"Kode Verifikasi Telah Dikirim ke email anda."));
				exit();
			}else{
				header('Content-Type: application/json');
				echo json_encode(array("stat"=>false,"msg"=>"Aksi gagal."));
				exit();
			}
		break;

		case 'upak': //update akun
			$namalengkap=$_POST['nama_lengkap'];
			$email=$_POST['email'];
			$newpwd=$_POST['newpwd'];
			$newpwd_repeat=$_POST['newpwd_repeat'];
			$oldpwd=$_POST['oldpwd'];
			$oldemail=base64_decode($_POST['olde']);

			$c=$sql->run("SELECT iduser FROM tb_userpublic WHERE email='".$email."' AND iduser <> '".U_ID."' ");
			$r=$c->rowCount();
			if($r>0){
				echo json_encode(array("stat"=>false,"msg"=>"Email yang ada inputkan sudah digunakan sebelumnya."));
				exit();
			}

			if($newpwd!=""){
				if($oldpwd==""){
					echo json_encode(array("stat"=>false,"msg"=>"Silakan Masukkan Password Lama Anda."));
					exit();
				}else{
					$sql->get_row('tb_userpublic',array('iduser'=>U_ID),array('pwd'));
					$check=$sql->result;
					$dboldpwd=$check['pwd'];
					if(md5(md5($oldpwd))!=$dboldpwd){
						echo json_encode(array("stat"=>false,"msg"=>"Password Lama Yang Anda Masukkan Salah, Harap Masukkan Password yang Benar."));
						exit();
					}
				}
				$newpassword=" ,pwd='".md5(md5($newpwd))."' ";
				$sql ->update('web_meta',array('meta_value'=>time()),array('ref_id'=>U_ID,'meta_key'=>'U_PWCHANGE','meta_group'=>1));
			}else{
				$newpassword="";
			}
			$ver_code=strtoupper(substr(md5(time()),0,5));
			$update=$sql->run("UPDATE tb_userpublic SET nama_lengkap='".$namalengkap."', email='".$email."', verifikasi='".$ver_code."' $newpassword WHERE iduser='".U_ID."'");
			if($sql->error==null){
				if($email!=$olde){ //jika ada perubahan email
					$sql ->update('web_meta',array('meta_value'=>0),array('ref_id'=>U_ID,'meta_key'=>'U_VERIFY','meta_group'=>1));
					require '../assets/phpmailer/PHPMailerAutoload.php';
					$isi="<p>".$namalengkap.", Berikut Adalah Kode Verifikasi Untuk Perubahan Email yang Anda Lakukan : <br><strong><h3>".$ver_code."</strong></h3></p>";
					$arr=array(
						"send_to"=>$email,
						"send_to_name"=>$nama_lengkap,
						"subject_email"=>"Verifikasi Akun - BPSPL Pontianak",
						"isi_email"=>$isi);
					sendMail($arr);

					$sql -> update( 'web_meta', array( 'meta_value'=>time() ), array( 'ref_id'=>U_ID,'meta_key'=>'U_LASTLOG','meta_group'=>1 ) );
					$sql -> update( 'web_meta', array( 'meta_value'=>getIP() ), array( 'ref_id'=>U_ID,'meta_key'=>'U_IP','meta_group'=>1 ) );
				    
			    	if($SITE_CONF_AUTOLOAD['tracking'] == "session"){ 
			    		session_destroy(); 
			    		$_SESSION[$SITE_CONF_AUTOLOAD['cookie']] = ""; 
			    	}
			    	isicookie($SITE_CONF_AUTOLOAD['cookie'], "", (time()-2592000));
				}
				echo json_encode(array("stat"=>true,"msg"=>"Profil Berhasil DiUpdate"));
				exit();
			}else{
				echo json_encode(array("stat"=>false,"msg"=>"Gagal Update Profil, Error: DB"));
				exit();
			}	
		break;

		default:
			echo json_encode(array("stat"=>false,"msg"=>"Mau Ngapain??"));
			exit();
		break;
	}
}
?>