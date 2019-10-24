<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

return [
    'defaultIncludes' => [
        __DIR__ . "/vendor/autoload.php",
        __DIR__ . "/bootstrap.php",
    ],
    
    'casters' => [
        Collection::class => '\App\Casters\EloquentCaster::castCollection',
        Model::class => '\App\Casters\EloquentCaster::castModel',
    ],

    'startupMessage' => "Welcome to Jawara administration console."
];
