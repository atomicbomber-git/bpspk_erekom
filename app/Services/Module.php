<?php

namespace App\Services;

class Module
{
    const MODULE_ADMIN = "MODULE_ADMIN";
    const MODULE_PENGAJUAN = "MODULE_PENGAJUAN";

    public function get()
    {
        return self::MODULE_PENGAJUAN;
    }
}