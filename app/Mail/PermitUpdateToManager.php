<?php

namespace App\Mail;

use App\Models\Permit;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PermitUpdateToManager extends Mailable
{
    use Queueable, SerializesModels;

    public $permit;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Permit $permit,  $subject = null)
    {
        $this->permit = $permit;
        $this->subject = $subject ?? 'You have received a notification from  '. env('APP_NAME');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $content  = '<p>A permit has been updated.</p>';
        $content .= '<p>Permit: '. $this->permit->type;
        $content .= '<br>Status: '. ($this->permit->status ?? 'not defined');

        $data = [
            'content' => $content,
            'signer' => env('APP_NAME'),
        ];

        return $this->subject($this->subject)
            ->view('emails.notification', $data);
    }
}
