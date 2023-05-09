<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Contact;

class SendProposal extends Mailable
{
    use Queueable, SerializesModels;
    public $contact;
    public $attachment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Contact $contact, $attachment)
    {
        $this->contact = $contact;
        $this->attachment = $attachment;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.sendproposal')
            ->attach($this->attachment, [
                'as' => $this->contact->StringName . '_proposal.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
