<?php

namespace App\Services;

use App\Services\Contracts\Template;
use League\Plates\Engine;

class PlatesTemplate implements Template
{
    private $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function render($identifier, $data): string
    {
        return $this->engine->render($identifier, $data);
    }
}
