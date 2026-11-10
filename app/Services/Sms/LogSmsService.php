<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

class LogSmsService implements SmsService
{
    public function send(string $phone, string $message): bool
    {
        Log::info("SMS to [{$phone}]: {$message}");
        return true;
    }
}