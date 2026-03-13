<?php

namespace App\Infrastructure\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class EmailSender
{
    public static function sendEmail(Mailable $mailable, string $emailAddress): void
    {
        Mail::to($emailAddress)->send($mailable);
    }
}
