<?php

namespace App\Util;


class RegistrationUtil
{

    public static function generateRegistrationKey()
    {
        return md5(uniqid(""));
    }
}