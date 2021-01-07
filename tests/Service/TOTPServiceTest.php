<?php

namespace Tests\Service;

use App\Service\TOTPService;
use PHPUnit\Framework\TestCase;

class TOTPServiceTest extends TestCase
{
    public function testGetSecret(): void
    {
        $service = new TOTPService();
        $this->assertIsString($service->getSecret());
    }

    public function testVerify(): void
    {
        $service = new TOTPService('OZAN5474PPYBFYKF7OLHCD4QDUFWGZRQ5MEHP3L6GXOHUBUNNYVT3PKLOYLI4O2S4EMP53ZQNKRZI3G5FUB75LJY5F6G56YEJAIKBCI');
        $this->assertTrue($service->verify('582790'));
    }

    public function testGetUri(): void
    {
        $service = new TOTPService();
        $uri = $service->getUri('test', 'saver.php');
        echo $uri;
        $this->assertStringStartsWith('otpauth://totp/saver.php%3Atest?issuer=saver.php&secret=', $uri);
    }
}
