<?php

use App\Enums\ModuleNames;
use App\Services\Auth;
use App\Services\Contracts\Template;
use App\Services\PlatesTemplate;
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager;
use Psr\Container\ContainerInterface;
use Jenssegers\Date\Date;
use Mpdf\Mpdf;

require_once(__DIR__ . "/vendor/autoload.php");

/* Whoops error page */
$whoops = new \Whoops\Run;

$jsonHandler = (new \Whoops\Handler\JsonResponseHandler);
$jsonHandler->setJsonApi(true);
$jsonHandler->addTraceToOutput();

$whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->prependHandler($jsonHandler);
$whoops->register();

/*
    Load environment-dependent configs
*/
$dotenv = Dotenv::create(__DIR__);
$dotenv->load();

/*
    Eloquent setup
*/
$capsule = new Manager;

$capsule->addConnection([
   "driver" => "mysql",
   "host" => getenv("DB_HOST"),
   "database" => getenv("DB_NAME"), 
   "username" => getenv("DB_USERNAME"),
   "password" => getenv("DB_PASSWORD"),
]);

//Make this Capsule instance available globally.
$capsule->setAsGlobal();

// Setup the Eloquent ORM.
$capsule->bootEloquent();

/* Set up localized date */
Date::setLocale("id");


$container = (new DI\ContainerBuilder())
    ->addDefinitions([
        "app_name" => "Loka Pengelolaan Sumberdaya Pesisir dan Laut Serang",
        
        "default_module" => ModuleNames::ADMIN,

        "app_path" => __DIR__,
    
        "mpdf_css_path" => function (ContainerInterface $container) {
            return sprintf(
                "%s/%s/%s/%s/%s/%s",
                $container->get("app_path"),
                "vendor",
                "mpdf",
                "mpdf",
                "data",
                "mpdf.css",
            ) ;
        },

        "font_assets_path" => function (ContainerInterface $container) {
            return sprintf(
                "%s/%s/%s/",
                $container->get("app_path"),
                "assets",
                "fonts",
            );
        },

        Mpdf::class => function (ContainerInterface $container) {
            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'] ?? [];

            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];

            return new Mpdf([
                'fontDir' => array_merge($fontDirs, [
                    $container->get("font_assets_path"),
                ]),
                'fontdata' => $fontData + [
                    'Arial' => [
                        'R' => 'arial.ttf',
                    ]
                ],
                'default_font' => 'Arial',
            
                'tempDir' => $container->get("temp_dir_path"),
            ]);
        },

        "temp_dir_path" => sys_get_temp_dir() . "/mpdf",

        "module" => function (ContainerInterface $c) {
            return explode("/", $_SERVER["SCRIPT_NAME"])
                [1] ?? $c->get("default_module");
        },

        "qrcode_dir_path" => __DIR__ . "/assets/images/img_qrcode",
        
        Auth::class => function (ContainerInterface $c) {
            return new Auth($c->get("module"));
        },
        
        League\Plates\Engine::class => function(ContainerInterface $container) {
            return new League\Plates\Engine(
                $container->get("app_path") . "/templates"
            );
        },

        Template::class => DI\autowire(PlatesTemplate::class),
    ])
    ->build();

function container($name) {
    global $container;
    return $container->get($name);
}

function app_path()
{
    return __DIR__;
}