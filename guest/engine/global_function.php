<?php
/**
* @package      Qapuas 5.0
* @version      Dev : 5.0
* @author       Rosi Abimanyu Yusuf <bima@abimanyu.net>
* @license      http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
* @copyright    2015
* @since        File available since Release 1.0
* @category     global_functions
*/

/**
 * IP detector
 */
function getIP(){
    if(getenv('HTTP_X_FORWARDED_FOR')){
        $ip = $_SERVER['REMOTE_ADDR'];
        if(preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", getenv('HTTP_X_FORWARDED_FOR'), $ip3)){
            $ip2 = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.16\..*/', '/^10..*/', '/^224..*/', '/^240..*/');
            $ip = preg_replace($ip2, $ip, $ip3[1]);
        }
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    if($ip == ""){ $ip = "x.x.x.x"; }
    return $ip;
}

/**
Cek php session/cookie
 */
function cek_session(){
    global $sql, $SITE_CONF_AUTOLOAD;

    if(!$_COOKIE[$SITE_CONF_AUTOLOAD['cookie']] && !$_SESSION[$SITE_CONF_AUTOLOAD['cookie']]){
        define("USER", FALSE);
    } else {
        list($username, $userpwd) = ($_COOKIE[$SITE_CONF_AUTOLOAD['cookie']] ? explode(".", $_COOKIE[$SITE_CONF_AUTOLOAD['cookie']]) : explode(".", $_SESSION[$SITE_CONF_AUTOLOAD['cookie']]));

        if(empty($username) || empty($userpwd)){
            isicookie($SITE_CONF_AUTOLOAD['cookie'], "", (time()-2592000));
            $_SESSION[$SITE_CONF_AUTOLOAD['cookie']] = "";
            @session_destroy();
            define("ADMIN", FALSE); define("USER", FALSE); define("U_LEVEL",""); 
            return(FALSE);
        }
        if($sql -> get_row('tb_guest',array('idg'=>$username,'pwd'=>md5($userpwd)), '*') ){
            $result = $sql ->result; extract($result);           
            define("USER", TRUE);
            define("U_ID", $idg);
            define("U_NAME", $nama_lengkap);
            define("U_PASS", $pwd);
            define("U_EMAIL", $email);
            define("U_TELP", $notelp);
            define("U_STATUS", $status);
            define("U_INSTANSI", $instansi);

            $sql -> get_all('web_meta',array('ref_id'=>U_ID,'meta_option'=>'autoload','meta_group'=>4), array('meta_key `KEY`','meta_value VAL') );
            foreach ($sql->result as $row) {
                $key=$row['KEY'];
                $val=$row['VAL'];
                define("$key",$val);
            }

            $NOWLOG = U_NOWLOG;

            if((U_NOWLOG + 7200) < time()){
                define("U_LASTLOG", U_NOWLOG);
                $NOWLOG = time();
                $sql -> update('web_meta',array('meta_value'=>$NOWLOG),array('ref_id'=>U_ID, 'meta_key'=>'U_NOWLOG','meta_group'=>4) );
                $sql -> update('web_meta',array('meta_value'=>$NOWLOG),array('ref_id'=>U_ID, 'meta_key'=>'U_LASTLOG','meta_group'=>4) );
            }
            
            if((isset($_SESSION['admlockscreen']) AND $_SESSION['admlockscreen']!="") OR (isset($_COOKIE['admlockscreen']) AND $_COOKIE['admlockscreen']!="")){
                define("U_LOCK", TRUE);
            }else{
                define("U_LOCK", FALSE);
            }

        } else{
            isicookie($SITE_CONF_AUTOLOAD['cookie'], "", (time()-2592000));
            $_SESSION[$SITE_CONF_AUTOLOAD['cookie']] = "";
            @session_destroy();
            define("USER", FALSE);
            return(FALSE);
        }

    }
}

function isicookie($name, $value, $expire, $path="/", $domain="", $secure=0){
        setcookie($name, $value, $expire, $path, $domain, $secure);
}

function createCode($panjangCode="2"){
    $code = '';
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
    $JumlahKarakter = strlen($chars)-1; 
    
    for($i = 0 ; $i < $panjangCode ; $i++){
        $code .= $chars[rand(0,$JumlahKarakter)];
    }
    return cekCode($code,1);
}

function removeAccent($str) {
    $a = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','Ā','ā','Ă','ă','Ą','ą','Ć','ć','Ĉ','ĉ','Ċ','ċ','Č','č','Ď','ď','Đ','đ','Ē','ē','Ĕ','ĕ','Ė','ė','Ę','ę','Ě','ě','Ĝ','ĝ','Ğ','ğ','Ġ','ġ','Ģ','ģ','Ĥ','ĥ','Ħ','ħ','Ĩ','ĩ','Ī','ī','Ĭ','ĭ','Į','į','İ','ı','Ĳ','ĳ','Ĵ','ĵ','Ķ','ķ','Ĺ','ĺ','Ļ','ļ','Ľ','ľ','Ŀ','ŀ','Ł','ł','Ń','ń','Ņ','ņ','Ň','ň','ŉ','Ō','ō','Ŏ','ŏ','Ő','ő','Œ','œ','Ŕ','ŕ','Ŗ','ŗ','Ř','ř','Ś','ś','Ŝ','ŝ','Ş','ş','Š','š','Ţ','ţ','Ť','ť','Ŧ','ŧ','Ũ','ũ','Ū','ū','Ŭ','ŭ','Ů','ů','Ű','ű','Ų','ų','Ŵ','ŵ','Ŷ','ŷ','Ÿ','Ź','ź','Ż','ż','Ž','ž','ſ','ƒ','Ơ','ơ','Ư','ư','Ǎ','ǎ','Ǐ','ǐ','Ǒ','ǒ','Ǔ','ǔ','Ǖ','ǖ','Ǘ','ǘ','Ǚ','ǚ','Ǜ','ǜ','Ǻ','ǻ','Ǽ','ǽ','Ǿ','ǿ');
    $b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');
    return str_replace($a, $b, $str);
}

function createSlug($str, $newstyle = "0") {
    if($newstyle == 1) {
        return preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), removeAccent($str));
    } else {
        return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), removeAccent($str)));
    }
}

function createSlugDB($str, $sql_table="", $prefix) {
    if(!empty($sql_table)){
        global $sql;
        $createSlug = createSlug($str);
        $sql->get_all($sql_table,array($prefix."slug"=>$createSlug),$prefix."slug");
        if($sql ->num_rows>=1) {
            $jum = $sql->num_rows + 1;
            return createSlugDB( createSlug ($str."-".$jum) , $sql_table, $prefix);
        }else {
            return $createSlug;
        }
    } 
}

/*
 * Redirect link
 */
function _redirect ( $link ) {
    if ($link == "NYASAR") { 
        echo "<script type='text/javascript'>document.location.href='". c_URL ."#SECURITY_WARNING/".USERNAME."=nyasar?'</script>\n";
    } else {
        echo "<script type='text/javascript'>document.location.href='". $link ."'</script>\n";
    }
}

function _ago($tm,$rcs = 0) {
    $tm = strtotime($tm);
    $cur_tm = time(); 
    $dif = $cur_tm-$tm;
    $pds = array('detik','menit','jam','hari','minggu','bulan','tahun');
    $lngh = array(1,60,3600,86400,604800,2630880,31570560);
    for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);
   
    $no = floor($no); $x=sprintf("%d %s ",$no,$pds[$v]);
    if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= _ago($_tm);
    return $x;
}

function selisih_tgl($date1,$date2){
    $diff = abs(strtotime($date2) - strtotime($date1));

    $data['tahun'] = floor($diff / (365*60*60*24));
    $data['bulan'] = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $data['hari'] = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

    return $data;
}


function TEXTAREA( $str ) {
    $str = htmlentities($str);
    $a = array('\n','\r');
    $b = array('<br />','<br />');
    return str_replace($a, $b, $str);
}

function tanggalIndo($waktu, $format) { //{tanggalIndoTiga tgl=0000-00-00 00:00:00 format="l, d/m/Y H:i:s"}
    if($waktu == "0000-00-00" || !$waktu || $waktu == "0000-00-00 00:00:00") {
        $rep = "";
    } else {
        if(preg_match('/-/', $waktu)) {
            $tahun = substr($waktu,0,4);
            $bulan = substr($waktu,5,2);
            $tanggal = substr($waktu,8,2);
        } else {
            $tahun = substr($waktu,0,4);
            $bulan = substr($waktu,4,2);
            $tanggal = substr($waktu,6,2);
        }

        $jam = substr($waktu,11,2);
        $menit= substr($waktu,14,2);
        $detik = substr($waktu,17,2);
        $hari_en = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
        $hari_id = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu");
        $bulan_en = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $bulan_id = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $ret = @date($format, @mktime($jam, $menit, $detik, $bulan, $tanggal, $tahun));

        $replace_hari = str_replace($hari_en, $hari_id, $ret);
        $rep = str_replace($bulan_en, $bulan_id, $replace_hari);
        $rep = nl2br($rep);
    }
    return $rep;
}

function is_image($imgtype){
    //just for png, jpg & gif
    $img=array("image/jpeg","image/pjpeg","image/png","image/gif");
    if(!in_array($imgtype,$img)){
        return FALSE; 
    }else { 
        return TRUE; 
    }
}

function is_video($videotype) {
    // untuk video, MP4, MPG, MPEG, AVI
    $video = array("video/mp4","video/mpeg","video/x-msvideo", "video/msvideo", "video/avi", "application/x-troff-msvideo") ;
    if( !in_array($videotype, $video) ) {
        return FALSE; 
    } else { 
        return TRUE; 
    }
}

function is_files($filestype) {
    // untuk files, pdf, doc, docx, txt, zip, rar
    $files = array("application/pdf", "text/plain", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/octet-stream") ;
    if( !in_array($filestype, $files) ) {
        return FALSE; 
    } else { 
        return TRUE; 
    }
}

function get_file_extension ($filename){
    $namewithoutext=explode('.',$filename);
    array_pop($namewithoutext);
    
    $forext=explode('.',$filename);
    $extension['file_without_ext']=implode('.',$namewithoutext );
    $extension['file_ext']=array_pop($forext);
    return $extension;
}
//remove white space when json encode
function clear_json($txt){
    $content = preg_replace("@[\\r|\\n|\\t]+@", "", $txt);
    return $content;
}

function Terbilang($x){
  $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
  if ($x < 12)
    return " " . $abil[$x];
  elseif ($x < 20)
    return Terbilang($x - 10) . " belas";
  elseif ($x < 100)
    return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
  elseif ($x < 200)
    return " seratus" . Terbilang($x - 100);
  elseif ($x < 1000)
    return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
  elseif ($x < 2000)
    return " seribu" . Terbilang($x - 1000);
  elseif ($x < 1000000)
    return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
  elseif ($x < 1000000000)
    return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
}

function sendMail($data){
    $tujuan=$data['send_to'];
    $tujuan_nama=$data['send_to_name'];
    $subject=$data['subject_email'];
    $isi_email=$data['isi_email'];

    date_default_timezone_set('Asia/Jakarta');
    
    $mail = new PHPMailer;
   // $mail->isSMTP();

    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 0;

    //$mail->Debugoutput = 'html';
    $mail->Host = 'tls://cpanel.vhost.id';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;

    $mail->Username = "admin@bpsplpontianak.com";
    $mail->Password = "admin2016bpspl";
    $mail->setFrom('admin@bpsplpontianak.com', 'BPSPL Pontianak');
    $mail->AddReplyTo("admin@bpsplpontianak.com","BPSPL Pontianak");

    $mail->isHTML(true); 
    $mail->addAddress($tujuan, $tujuan_nama);
    $mail->Subject = $subject;

    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->msgHTML($isi_email);
    //Replace the plain text body with one created manually
    //$mail->AltBody =$isi_email;
    //Attach an image file
    //$mail->addAttachment('images/phpmailer_mini.png');
    //send the message, check for errors
    /*if (!$mail->send()) {
        return FALSE;
    } else {
        return TRUE;
    }*/

    return TRUE;
}

?>