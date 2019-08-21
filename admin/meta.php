<?php
/**
* @package      Qapuas 5.0
* @version      Dev : 5.0
* @author       Rosi Abimanyu Yusuf <bima@abimanyu.net>
* @license      http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC 3.0
* @copyright    2015
* @since        File available since 5.0
* @category     Themes Meta Header
*/
echo "<!doctype html>
<html class=\"fixed inner-menu-opened\" itemscope itemtype=\"http://schema.org/Product\" lang=\"id\" xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:og=\"http://ogp.me/ns#\"  xmlns:rdfs=\"http://www.w3.org/2000/01/rdf-schema#\" xmlns:vcard=\"http://www.w3.org/2006/vcard/ns#\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema#\" xmlns:v=\"http://rdf.data-vocabulary.org/#\" xmlns:fb=\"http://www.facebook.com/2008/fbml\" xml:lang=\"id\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<meta charset=\"UTF-8\">
<title>".c_APP."</title>
<meta name=\"keywords\" content=\"".c_APP." Applications\" />
<meta name=\"description\" content=\"".c_APP." - Pengajuan Permohonan\">
<meta name=\"author\" content=\"abimanyu.net\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no\" />
<link href=\"http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light\" rel=\"stylesheet\" type=\"text/css\">";

ITEM_HEAD($ITEM_HEAD);
SCRIPT_HEAD($SCRIPT_HEAD);

echo "
<link rel='apple-touch-icon' href='".IMAGES."favicon.png' />
<link rel='icon' type='image/vnd.microsoft.icon' href='".IMAGES."favicon.png' />
</head>
<body>";
?>