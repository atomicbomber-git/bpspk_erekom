<?php
/**
* @package      Qapuas 5.0
* @version      Dev : 5.0
* @author       Rosi Abimanyu Yusuf <bima@abimanyu.net>
* @license      http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
* @copyright    2015
* @since        File available since Release 1.0
* @category     init_app_config
*/

$ModuleDir = "modul/";

$SITE_CONF_AUTOLOAD['NAMA_APP'] = "E-Rekomendasi";
$SITE_CONF_AUTOLOAD['timezone'] = "Asia/Jakarta";
$SITE_CONF_AUTOLOAD['ssl'] = "";
//$SITE_CONF_AUTOLOAD['tracking'] = "session";
$SITE_CONF_AUTOLOAD['tracking'] = "cookie";

$SQL_CONFIG['host'] = getenv("DB_HOST");
$SQL_CONFIG['user'] 	= getenv("DB_USERNAME"); 
$SQL_CONFIG['password'] = getenv("DB_PASSWORD"); 
$SQL_CONFIG['db']  	= getenv("DB_NAME");

define("c_APP", $SITE_CONF_AUTOLOAD['NAMA_APP']);
define("c_APPVER", "Ver.1.0.0");
define("c_CLIENT", $SITE_CONF_AUTOLOAD['NAMA_CLIENT']);
define("c_ALAMAT", $SITE_CONF_AUTOLOAD['ALAMAT_CLIENT']);

define("c_DOMAIN", $DOMAIN);
define("c_DOMAIN_UTAMA", $BASE_URL_UTAMA);
define("c_URL", $BASE_URL.$BASE_PATH);
define("c_BASE", $SCRIPTS_PATH.$BASE_PATH);
define("c_BASE_UTAMA", $SCRIPTS_PATH_UTAMA.$BASE_PATH);
define("c_STATIC", $BASE_URL.$BASE_PATH.$THEMES);
define("c_THEMES", c_BASE.$THEMES);
define("c_MODULE", c_URL.$ModuleDir);
// define("c_PUBLIC", c_BASE.$PUBLIC);

?>