<?php

require_once(c_THEMES."conf.php");

if(USER){

	switch (c_QUERY) {
		case 'keluar':
			//$sql -> update( 'web_meta', array( 'meta_value'=>time() ), array( 'ref_id'=>U_ID,'meta_key'=>'U_LASTLOG' ) );
			//$sql -> update( 'web_meta', array( 'meta_value'=>getIP() ), array( 'ref_id'=>U_ID,'meta_key'=>'U_IP' ) );
		    
	    	if($SITE_CONF_AUTOLOAD['tracking'] == "session"){ 
	    		session_destroy(); 
	    		$_SESSION[$SITE_CONF_AUTOLOAD['cookie']] = ""; 
	    	}
	    	isicookie($SITE_CONF_AUTOLOAD['cookie'], "", (time()-2592000));
	    	echo "<script type='text/javascript'>document.location.href='".c_SELF."#TakeOff'</script>\n";
    		exit;
		break;

		case 'lock':
			//require_once(AdminHeader);

			if($SITE_CONF_AUTOLOAD['tracking'] == "session"){
				$_SESSION['admlockscreen']=U_ID;
			}else{
				isicookie('admlockscreen', U_ID, ( time()+3600*24*30));
			}
			echo "<script type='text/javascript'>document.location.href='".c_SELF."#lockadmin'</script>\n"; 
		break;

		default:
			//404
			//exit();
		break;
	}

	// if(U_LOCK){
	// 	include ("modul/user/form_lock.php");
	// 	exit;
	// }else{
		require_once(AdminHeader);
	// }

}else{
	include ("modul/user/form_login.php");
	exit;
}
?>