<?php

use App\Enums\ModuleNames;
use App\Services\Auth;
use App\Services\Contracts\Template;
use App\Services\PlatesTemplate;
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager;
use Psr\Container\ContainerInterface;

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

$container = (new DI\ContainerBuilder())
    ->addDefinitions([
        "app_name" => "Loka Pengelolaan Sumberdaya Pesisir dan Laut Serang",
        
        "default_module" => ModuleNames::ADMIN,  
        "module" => function (ContainerInterface $c) {
            return explode("/", $_SERVER["SCRIPT_NAME"])
                [1] ?? $c->get("default_module");
        },

        "qrcode_dir_path" => __DIR__ . "/assets/images/img_qrcode",
        
        Auth::class => function (ContainerInterface $c) {
            return new Auth($c->get("module"));
        },
        
        League\Plates\Engine::class => function() {
            return new League\Plates\Engine(
                __DIR__ . "/templates"
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