<?php

namespace App\Notifications;

use App\Models\ProposalNote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProposalNoteNotification extends Notification
{
    protected ProposalNote $proposalNote;

    public function __construct($proposalNote)
    {
        $this->proposalNote = $proposalNote;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $proposalNote = $this->proposalNote;

        $subject = 'Proposal note reminder.';

        $content = '<p>Hello, '.$notifiable->fname.'</p>';
        $content .= '<p>We have sent this notification because you have a proposal note with a reminder set for today.</p>';
        $content .= '<p>Proposal name is: '.$proposalNote->proposal->name.'.</p>';
        $content .= '<p>Proposal note is:<br>';
        $content .= $proposalNote->note.'.</p>';
        $signer = '<p>System Admin</p>';

        $tags = [
            'subject' => $subject,
            'content' => $content,
            'signer' => $signer,
        ];

        return (new MailMessage)
            ->subject($subject)
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->view('emails.notification', $tags);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
