<?php

namespace App\Services;

use Jenssegers\Date\Date;

class Formatter
{
    public function date($date)
    {
        return (new Date($date))
            ->format("Y-m-d");
    }

    public function fancyDate($date)
    {
        return (new Date($date))
            ->format("j F Y");
    }
}