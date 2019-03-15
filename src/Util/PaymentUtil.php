<?php

namespace App\Util;


class PaymentUtil
{

    public static function generatePaymentReference()
    {
        return uniqid("");
    }
}