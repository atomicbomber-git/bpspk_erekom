<?php

use App\Enums\ModuleNames;
use App\Services\Auth;
use App\Services\Contracts\KodeSegelGenerator as ContractsKodeSegelGenerator;
use App\Services\Contracts\Template;
use App\Services\KodeSegelGenerator;
use App\Services\PlatesTemplate;
use DI\Container;
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager;
use Psr\Container\ContainerInterface;
use Jenssegers\Date\Date;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\Request;

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

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

/* Load bugsnag error tracker */
$bugsnag = Bugsnag\Client::make(getenv("BUGSNAG_API_KEY"));
Bugsnag\Handler::register($bugsnag);

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
        Request::class => function () {
            return Request::createFromGlobals();
        },

        "app_url" => function(ContainerInterface $container) {
            return $container->get(Request::class)
                ->getSchemeAndHttpHost();
        },

        "app_name" => "Loka Pengelolaan Sumberdaya Pesisir dan Laut Serang",
        "app_short_name" => "LPSPL Serang",
        "upt_code" => "05",
        
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

        ContractsKodeSegelGenerator::class => DI\autowire(KodeSegelGenerator::class),

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

        "admin_email_address" => getenv('ADMIN_EMAIL_ADDRESS') ?: "admin@default.com",
        "smtp_host" => getenv("SMTP_HOST") ?: "default_smtp_host",
        "smtp_username" => getenv("SMTP_USERNAME") ?: "default_smtp_username",
        "smtp_password" => getenv("SMTP_PASSWORD") ?: "default_smtp_password",
        "smtp_port" => getenv("SMTP_PORT") ?: 587,

        Swift_Transport::class => function(ContainerInterface $container) {
            return (
                    new Swift_SmtpTransport(
                        $container->get("smtp_host"),
                        $container->get("smtp_port"),
                    )
                )
                ->setUsername($container->get("smtp_username"))
                ->setPassword($container->get("smtp_password"));
        },

        Swift_Mailer::class => function (ContainerInterface $container) {
            return new Swift_Mailer($container->get(Swift_Transport::class));
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