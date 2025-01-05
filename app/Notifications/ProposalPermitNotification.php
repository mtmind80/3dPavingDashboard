<?php

namespace App\Notifications;

use App\Helpers\MailParams;
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
        $viewVariables = MailParams::get();

        $content = '<p>Hello, '.$notifiable->fname.'</p>';
        $content .= '<p>We have sent this notification because you have a proposal that had a permit updated today.</p>';
        $content .= '<p>Proposal name is: ' . $this->permit->proposal->name.'.</p>';
        $content .= '<p>Permit is: ' . $this->permit->status . '.</p>';
        $content .= '<p>County: ' .$this->permit->county . '.</p>';
        $content .= '<p>Expires on: ' . ($this->permit->expires_on !== null ? $this->permit->expires_on->format('m/d/Y') : '') . '.</p>';

        if ($this->permit->notes->count() > 0) {
            $content .= '<p>Notes:</p>';

            foreach($this->permit->notes as $note) {
                $content .= '<p>' . $note->note . '.</p>';
            }
        }

        $viewVariables['content'] = $content;
        
        $viewVariables['signer'] = '<p>System Admin</p>';

        return (new MailMessage)
            ->subject('Permit Update.')
            ->from($viewVariables['mail_from_address'], $viewVariables['mail_from_name'])
            ->view('emails.notification_with_view_variables', ['viewVariables' => $viewVariables]);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
