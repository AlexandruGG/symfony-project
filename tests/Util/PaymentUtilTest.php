<?php

namespace App\Tests\Util;

use App\Util\PaymentUtil;
use PHPUnit\Framework\TestCase;

class PaymentUtilTest extends TestCase
{
    public function testGeneratePaymentReference()
    {
        $paymentReference = PaymentUtil::generatePaymentReference();

        $this->assertInternalType('string', $paymentReference);
        $this->assertEquals(13, strlen($paymentReference));
    }
}