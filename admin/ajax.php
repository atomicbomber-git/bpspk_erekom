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
					$_SESSION['admlockscreen']="";
				}else{
					isicookie("admlockscreen", "", (time()-3600*24*30));
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

			$sandiuser = md5(md5(trim($_POST['authsandi'])));
            $authuser = $_POST['authuser'];
            
			$sql -> get_row( 'op_user', array( 'username'=>$authuser), array('idu','pwd'));

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
					$cookieval = $row['idu'].".".$cookiepass;
					
					$datalogin=array(
						'uid'=>$row['idu'],
						'date_login'=>date('y-m-d H:i:s'),
						'user_ip'=>getIP(), 
						'user_agent'=>$_SERVER['HTTP_USER_AGENT']
						);

					$sql -> insert('users_stat_login',$datalogin);
					$sql -> run("UPDATE web_meta SET meta_value=meta_value+1 WHERE ref_id='".$row['idu']."' AND meta_key='U_TOTAL_LOGIN' AND meta_group='2'");

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
		break;

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
				$url=c_URL."view.php?surat=".$kdsurat."&token=".md5('view'.$kdsurat.'admin');
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