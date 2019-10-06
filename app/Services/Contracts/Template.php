<?php

namespace App\Services\Contracts;

interface Template
{
    public function render($identifier, $data): string;
}