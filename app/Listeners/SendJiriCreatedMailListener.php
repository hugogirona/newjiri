<?php

namespace App\Listeners;

use App\Events\JiriCreatedEvent;
use App\Mail\JiriCreatedMail;
use Illuminate\Support\Facades\Mail;

class SendJiriCreatedMailListener
{
    public function __construct()
    {
    }

    public function handle(JiriCreatedEvent $event): void
    {
        Mail::to(request()->user())->queue(new JiriCreatedMail($event->jiri));
    }
}
