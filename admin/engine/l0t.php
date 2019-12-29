<?php
/**
* @package      Qapuas 5.0
* @version      Dev : 5.0
* @author       Rosi Abimanyu Yusuf <bima@abimanyu.net>
* @license      http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
* @copyright    2015
* @since        File available since Release 1.0
* @category     config
*/
// Semua path menggunakan tanda "/" di akhir

require_once(__DIR__ . "/../../bootstrap.php");

$BASE_PATH = "";
$SCRIPTS_PATH = app_path() . "/admin/";
$SCRIPTS_PATH_UTAMA = app_path() . "/";
$BASE_URL_UTAMA = container("app_url") . "/";
$BASE_URL = container("app_url") . "/admin/";
$THEMES = "";
//$PUBLIC = "public/";
$DOMAIN =container("app_url") . "/admin/";

$SITE_CONF_AUTOLOAD['NAMA_CLIENT'] = "E-Rekomendasi";
$SITE_CONF_AUTOLOAD['WEBSITE_CLIENT'] = "";
$SITE_CONF_AUTOLOAD['cookie'] = "i0t3rek4dm1n";
$SITE_CONF_AUTOLOAD['ALAMAT_CLIENT'] = "";
?>