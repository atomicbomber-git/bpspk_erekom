<?php

use Dotenv\Dotenv;

require_once(__DIR__ . "/vendor/autoload.php");

/*
    Load environment-dependent configs
*/
$dotenv = Dotenv::create(__DIR__);
$dotenv->load();


function app_path()
{
    return __DIR__;
}