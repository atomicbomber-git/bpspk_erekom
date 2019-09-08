<?php

ob_start();
$menghitung_waktu_diaktifkan = explode(' ', microtime());
error_reporting(E_ERROR | E_WARNING | E_PARSE);

if (strstr($_SERVER['QUERY_STRING'], "'") || strstr($_SERVER['QUERY_STRING'], ";") || strstr($_SERVER['QUERY_STRING'], "%27")) {
    die("Access denied.");
} //ih heker
if (preg_match("/\[(.*?)\].*?/i", $_SERVER['QUERY_STRING'], $comelizer)) {
    define("c_ACTION", $comelizer[0]);
    define("c_QUERY", str_replace($comelizer[0], "", preg_replace("&|/?PHPSESSID.*", "", $_SERVER['QUERY_STRING'])));
} else {
    define("c_QUERY", $_SERVER['QUERY_STRING']);
}

$_SERVER['QUERY_STRING'] = c_QUERY;

define("c_SELF", str_replace(c_URL, c_URL, ($SITE_CONF_AUTOLOAD['ssl'] ? "https://".$_SERVER['HTTP_HOST'].($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_FILENAME']) : "http://".$_SERVER['HTTP_HOST'].($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_FILENAME']))));
define("c_XELF", substr(c_SELF, 0, -4));



$sql = new dbPDO();

if (!defined("c_BASE")) {
    header("Location:/404");
    exit;
}

if (function_exists('date_default_timezone_set')) {
    @date_default_timezone_set($SITE_CONF_AUTOLOAD['timezone']);
}


if (!$SITE_CONF_AUTOLOAD['cookie']) {
    $SITE_CONF_AUTOLOAD['cookie'] = "i0tc00k3m0b";
}

if ($SITE_CONF_AUTOLOAD['tracking'] == "session") {
    session_start();
}

$ip = getIP();
cek_session();