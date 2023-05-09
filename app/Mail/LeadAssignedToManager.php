<?php

namespace App\Mail;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadAssignedToManager extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $manager;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Lead $lead, $subject = null)
    {
        $this->lead = $lead;
        $this->manager = $lead->assignedTo;
        $this->subject = $subject ?? 'You have received a notification from  '. env('APP_NAME');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $content  = '<p>You have been assigned to a lead.</p>';
        $content .= '<p>Name: '. $this->lead->full_name;
        $content .= '<br>Email: '. ($this->lead->email ?? 'not defined');
        $content .= '<br>Phone: '. ($this->lead->phone ?? 'not defined') .'</p>';

        $data = [
            'content' => $content,
            'signer' => env('APP_NAME'),
        ];

        return $this->subject($this->subject)
            ->view('emails.notification', $data);
    }
}
