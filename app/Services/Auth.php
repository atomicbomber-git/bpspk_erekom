<?php

namespace App\Services;

use App\Enums\ModuleNames;
use App\Models\UserPublic;

class Auth
{
    private $module;

    public function __construct($module)
    {
        $this->module = $module;
    }

    private function getUserId() {
        list($userId) = explode(".", $_COOKIE["i0tcook3rek"]);
        return $userId;
    }

    public function user()
    {
        if (!$this->getUserId()) {
            return null;
        }

        switch ($this->module) {
            case (ModuleNames::PENGAJUAN):
                return UserPublic::query()
                    ->where((new UserPublic)->getKeyName(), $this->getUserId())
                    ->with("verification")
                    ->first();
            default:
                return null;
        }
    }

    public function isVerified()
    {
        $user = $this->user();

        if (!isset($user->verification->meta_value)) {
            return false;
        }
        else {
            return $user->verification->meta_value ? true : false;
        }
    }
}