<?php

namespace App\Services\SmsProviders;

use App\Contracts\SmsProviderInterface;
use App\Models\SmsCode;

class DemoSmsProvider implements SmsProviderInterface
{
    public function sendVerificationCode(string $phone): string
    {
        SmsCode::clearExpired();

        $code = $this->generateCode();

        SmsCode::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
            'used' => false
        ]);

        return $code;
    }

    public function verifyCode(string $phone, string $code): bool
    {
        SmsCode::clearExpired();

        $smsCode = SmsCode::findActiveCode($phone, $code);

        if (!$smsCode) {
            return false;
        }

        $smsCode->markAsUsed();

        return true;
    }

    private function generateCode(): string
    {
        return sprintf("%06d", random_int(1, 10 ** 6 - 1));
    }
}