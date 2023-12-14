<?php

namespace App\Notifications;

use App\Models\Permit;
use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProposalPermitNotification extends Notification
{
    protected Permit $permit;

    public function __construct($permit)
    {
        $this->permit = $permit;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $permit = $this->permit;

        $subject = 'Permit Update.';

        $content = '<p>Hello, '.$notifiable->fname.'</p>';
        $content .= '<p>We have sent this notification because you have a proposal that had a permit updated today.</p>';
        $content .= '<p>Proposal name is: '.$permit->proposal->name.'.</p>';
        $content .= '<p>Permit is:<br>';
        $content .= $permit->status.'.</p>';
        $content .= $permit->county.'.</p>';
        $content .= $permit->expires_on.'.</p>';
        foreach($permit->notes as $note)
        {
            $content .= $note->note.'.</p>';
        }
        $signer = '<p>System Admin</p>';

        $tags = [
            'subject' => $subject,
            'content' => $content,
            'signer' => $signer,
        ];

        return (new MailMessage)
            ->subject($subject)
            ->from(env('MAIL_FROM_ADDRESS','no-reply@3-dpaving.com'), env('MAIL_FROM_NAME','3D-Paving.Com'))
            ->view('emails.notification', $tags);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
