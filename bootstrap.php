<?php

use Dotenv\Dotenv;
use Whoops\Handler\PrettyPageHandler;

require_once(__DIR__ . "/vendor/autoload.php");

$whoops = new \Whoops\Run;
$pretty_page_handler = new \Whoops\Handler\PrettyPageHandler;
$whoops->prependHandler($pretty_page_handler);
$whoops->register();

/*
    Load environment-dependent configs
*/
$dotenv = Dotenv::create(__DIR__);
$dotenv->load();

function app_path()
{
    return __DIR__;
}