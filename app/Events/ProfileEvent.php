<?php

namespace App\Events;

class ProfileEvent extends Event
{
    public string $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}
