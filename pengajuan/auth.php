<?php
/**
* @package      Qapuas 5.0
* @version      Dev : 5.0
* @author       Rosi Abimanyu Yusuf <bima@abimanyu.net>
* @license      http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
* @copyright    2015
* @since        File available since Release 1.0
* @category     init_user_auth
*/

require_once(c_THEMES."conf.php");

if(USER){

	switch (c_QUERY) {
		case 'keluar':
			$sql -> update( 'web_meta', array( 'meta_value'=>time() ), array( 'ref_id'=>U_ID,'meta_key'=>'U_LASTLOG','meta_group'=>1 ) );
			$sql -> update( 'web_meta', array( 'meta_value'=>getIP() ), array( 'ref_id'=>U_ID,'meta_key'=>'U_IP','meta_group'=>1 ) );
		    
	    	if($SITE_CONF_AUTOLOAD['tracking'] == "session"){ 
	    		session_destroy(); 
	    		$_SESSION[$SITE_CONF_AUTOLOAD['cookie']] = ""; 
	    	}
	    	isicookie($SITE_CONF_AUTOLOAD['cookie'], "", (time()-2592000));
	    	echo "<script type='text/javascript'>document.location.href='".c_SELF."#TakeOff'</script>\n";
    		exit;
		break;

		case 'lock':
			require_once(AdminHeader);
			if($SITE_CONF_AUTOLOAD['tracking'] == "session"){
				$_SESSION['lockscreen']=U_ID;
			}else{
				isicookie('lockscreen', U_ID, ( time()+3600*24*30));
			}
			echo "<script type='text/javascript'>document.location.href='".c_SELF."#Lock'</script>\n"; 
		break;

		case 'pengajuan':
			 require_once(AdminHeader);
			include ("modul/pengajuan/form.php");
			exit();
		break;

		case 'kuisioner':
			require_once(AdminHeader);
			include ("modul/kuisioner/index.php");
			exit();
		break;

		case 'biodata':
			require_once(AdminHeader);
			include ("modul/biodata/form.php");
			exit();
		break;

		case 'riwayat':
			require_once(AdminHeader);
			include ("modul/riwayat/index.php");
			exit();
		break;

		case 'verifikasi':
			require_once(AdminHeader);
			include ("modul/user/form_verifikasi.php");
			exit();
		break;

		case 'akun':
			require_once(AdminHeader);
			include ("modul/user/form_profile.php");
			exit();
		break;

		default:
			//404
			//exit();
		break;
	}

	if(U_LOCK){
		include ("modul/user/form_lock.php");
		exit;
	}else{
		require_once(AdminHeader);
	}

}else{

	switch(c_QUERY){
		case 'daftar':
			include ("modul/user/form_registrasi.php");
			exit;
		break;

		case 'recover':
			include ("modul/user/form_recover.php");
			exit();
		break;

		default:
			include ("modul/user/form_login.php");
			exit;
		break;
	}
}
?>