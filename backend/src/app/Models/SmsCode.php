<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'code', 
        'expires_at',
        'used'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean'
    ];

    public static function findActiveCode(string $phone, string $code): ?self
    {
        return static::where('phone', $phone)
            ->where('code', $code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();
    }

    public function markAsUsed(): bool
    {
        return $this->update(['used' => true]);
    }

    public static function clearExpired(): void
    {
        static::where('expires_at', '<=', now())->delete();
    }
}