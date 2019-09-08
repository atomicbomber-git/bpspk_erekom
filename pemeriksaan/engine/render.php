<?php
/**
* @package      Qapuas 5.0
* @version      Dev : 5.0
* @author       Rosi Abimanyu Yusuf <bima@abimanyu.net>
* @license      http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
* @copyright    2015
* @since        File available since Release 1.0
* @category     init_render
*/

// while (list($global) = each($GLOBALS)){
//     if (!preg_match('/^(_POST|_GET|_COOKIE|_SERVER|_FILES|GLOBALS|HTTP.*|_REQUEST)$/', $global)){
//         unset($$global);
//     }
// }

//ob_start ("ob_gzhandler") //khusus type gzip
ob_start ();
$menghitung_waktu_diaktifkan = explode(' ', microtime());
 error_reporting(E_ERROR | E_WARNING | E_PARSE);
// ini_set('display_errors', 1); 
// error_reporting(E_ALL);
if(strstr($_SERVER['QUERY_STRING'], "'") || strstr($_SERVER['QUERY_STRING'], ";") || strstr($_SERVER['QUERY_STRING'], "%27") ){ 
	die("Access denied."); 
} //ih heker
if(preg_match("/\[(.*?)\].*?/i", $_SERVER['QUERY_STRING'], $comelizer)){
	define("c_ACTION", $comelizer[0]);
    define("c_QUERY", str_replace($comelizer[0], "", preg_replace("&|/?PHPSESSID.*", "", $_SERVER['QUERY_STRING'])));
}else{
    define("c_QUERY", $_SERVER['QUERY_STRING']);
}

$_SERVER['QUERY_STRING'] = c_QUERY;

include ("l0t.php");
include ("define.php");

define("c_SELF", str_replace(c_URL, c_URL, ($SITE_CONF_AUTOLOAD['ssl'] ? "https://".$_SERVER['HTTP_HOST'].($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_FILENAME']) : "http://".$_SERVER['HTTP_HOST'].($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_FILENAME']))));
//define("c_SELF", str_replace(c_URL."/", c_URL, ($SITE_CONF_AUTOLOAD['ssl'] ? "https://".$_SERVER['HTTP_HOST'].($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_FILENAME']) : "http://".$_SERVER['HTTP_HOST'].($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_FILENAME']))));
define("c_XELF", substr(c_SELF, 0, -4));

include("smplPDO.php");
$sql = new dbPDO();

if(!defined("c_BASE")){ 
	header("Location:/404"); 
	exit; 
}

if(function_exists('date_default_timezone_set')) @date_default_timezone_set($SITE_CONF_AUTOLOAD['timezone']);

require_once ("global_function.php");

if(!$SITE_CONF_AUTOLOAD['cookie']){ 
	$SITE_CONF_AUTOLOAD['cookie'] = "i0tc00k3m0b"; 
}

if($SITE_CONF_AUTOLOAD['tracking'] == "session"){ 
	session_start(); 
}

$ip = getIP();
cek_session();
//require_once (c_THEMES."auth.php");
?>