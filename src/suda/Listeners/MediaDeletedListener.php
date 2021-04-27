<?php

namespace Gtd\Suda\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Gtd\Suda\Events\MediaDeleted;
use Gtd\Suda\Models\Media;
use Log;

class MediaDeletedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MediaDeleted  $event
     * @return void
     */
    public function handle(MediaDeleted $event)
    {
        $media = $event->media;
        Media::removeFiles($media);
    }
}
