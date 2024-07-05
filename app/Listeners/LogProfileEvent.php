<?php

namespace App\Listeners;

use App\Events\ProfileEvent;
use Illuminate\Support\Facades\Log;

class LogProfileEvent
{
    public function handle(ProfileEvent $event): void
    {
        Log::channel('access')->info($event->message);
    }
}
