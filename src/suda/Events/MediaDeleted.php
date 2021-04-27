<?php

namespace Gtd\Suda\Events;


use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Gtd\Suda\Models\Media;
use Log;

class MediaDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $media;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($media)
    {
        //define media
        $this->media = $media;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
