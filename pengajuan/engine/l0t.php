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

require_once(__DIR__ . "/../../bootstrap.php");

// Semua path menggunakan tanda "/" di akhir
$BASE_PATH = "";
$SCRIPTS_PATH =  app_path() . "/pengajuan/";
$BASE_URL_UTAMA = container("app_url") . "/";
$BASE_URL = container("app_url") . "/pengajuan/";
$THEMES = "";
// $PUBLIC = "public/";
$DOMAIN =container("app_url") . "/pengajuan/";

$SITE_CONF_AUTOLOAD['NAMA_CLIENT'] = "E-Rekomendasi";
$SITE_CONF_AUTOLOAD['WEBSITE_CLIENT'] = "#";
$SITE_CONF_AUTOLOAD['cookie'] = "i0tcook3rek";
$SITE_CONF_AUTOLOAD['ALAMAT_CLIENT'] = "";
?>