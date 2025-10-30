<?php

namespace App\Contracts;

interface SmsProviderInterface
{
    public function sendVerificationCode(string $phone): string;
    public function verifyCode(string $phone, string $code): bool;
}