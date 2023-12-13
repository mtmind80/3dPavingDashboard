<?php

namespace App\Notifications;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OldProposalNotification extends Notification
{
    use Queueable;

    protected Proposal $proposal;

    public function __construct($proposal)
    {
        $this->proposal = $proposal;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $proposal = $this->proposal;

        $subject = 'Proposal older than 60 days.';

        $content = '<p>Hello, '.$notifiable->fname.'</p>';
        $content .= '<p>We have sent this notification because you have a proposal in a pending status older than 60 days.</p>';
        $content .= '<p>Proposal name is: '.$proposal->name.'.</p>';
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
