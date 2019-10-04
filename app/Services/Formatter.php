<?php

namespace App\Services;

use Carbon\Carbon;

class Formatter
{
    public function date($date)
    {
        return (new Carbon($date))
            ->format("Y-m-d");
    }
}