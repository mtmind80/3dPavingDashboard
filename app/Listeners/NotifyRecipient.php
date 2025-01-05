<?php

namespace App\Listeners;

use App\Events\SomethingToNotify;
use App\Helpers\MailParams;
use App\Mailer\Mailer;
use Illuminate\Support\Facades\Mail;

class NotifyRecipient
{
    public function __construct()
    {
        //
    }

    public function handle(SomethingToNotify $event)
    {
        $viewVariables = MailParams::get();

        $viewVariables['subject'] = $event->subject;
        $viewVariables['content'] = $event->content;
        $viewVariables['signer'] = '<p>System Admin</p>';

        Mail::to($event->recipient)->send(new Mailer('emails.notification_with_view_variables', $viewVariables));
    }
}
