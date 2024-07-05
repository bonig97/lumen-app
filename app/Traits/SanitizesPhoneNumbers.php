<?php

namespace App\Traits;

trait SanitizesPhoneNumbers
{
    public function sanitizePhoneNumber($phoneNumber): array|string|null
    {
        return preg_replace('/^\+39/', '', $phoneNumber);
    }
}
