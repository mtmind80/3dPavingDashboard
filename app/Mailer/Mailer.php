<?php

namespace App\Mailer;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Mailer extends Mailable
{
    use Queueable, SerializesModels;

    protected $blade_view;
    public $viewVariables;
    protected $attachedFiles;

    public function __construct($bladeView, $viewVariables, $attachedFiles = null)
    {
        $this->blade_view = $bladeView;
        $this->viewVariables = $viewVariables;
        $this->attachedFiles = $attachedFiles;
    }

    public function build()
    {
        $email = $this
            ->from($this->viewVariables['mail_from_address'], $this->viewVariables['mail_from_name'])
            ->subject($this->viewVariables['subject'])
            ->view($this->blade_view)
            ->with(['viewVariables' => $this->viewVariables]);

        if ($this->attachedFiles !== null) {
            foreach ((array)$this->attachedFiles as $attachedFile) {
                $email->fromStorageDisk('attachments_disk', $attachedFile);
            }
        }

        return $email;
    }
}
