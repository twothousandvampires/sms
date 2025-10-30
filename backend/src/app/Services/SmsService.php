<?php

namespace App\Services;

use App\Contracts\SmsProviderInterface;

class SmsService
{
    private SmsProviderInterface $provider;

    public function __construct(SmsProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function sendVerificationCode(string $phone): string
    {
        return $this->provider->sendVerificationCode($phone); 
    }

    public function verifyCode(string $phone, string $code): bool
    {
        return $this->provider->verifyCode($phone, $code); 
    }
}