<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

//  Listener:  NotifyDeveloper

class SomethingToNotify
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $recipient;
    public $subject;
    public $content;

    public function __construct($recipient, $subject, $content)
    {
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->content = $content;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
