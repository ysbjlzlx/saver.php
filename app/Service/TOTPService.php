<?php

namespace App\Service;

use OTPHP\TOTP;

class TOTPService implements TOTPServiceInterface
{
    /**
     * @var TOTP
     */
    private $instance;

    public function __construct(?string $secret = null)
    {
        $this->instance = TOTP::create($secret);
    }

    public function verify(string $otp, ?int $timestamp = null, ?int $window = null): bool
    {
        return $this->instance->verify($otp, $timestamp, $window);
    }

    public function getUri(string $label, ?string $issuer = null): string
    {
        $this->instance->setLabel($label);
        if (!is_null($issuer)) {
            $this->instance->setIssuer($issuer);
        }

        return $this->instance->getProvisioningUri();
    }

    public function getSecret(): string
    {
        return $this->instance->getSecret();
    }
}
