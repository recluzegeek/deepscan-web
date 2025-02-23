<?php

namespace App\Listeners;

use App\Events\VideoDownloaded;
use App\Jobs\FrameExtractionJob;

class ProcessVideo
{
    /**
     * Handle the event.
     *
     * @param VideoDownloaded $event
     * @return void
     */
    public function handle(VideoDownloaded $event)
    {
        // Queue the job for frame extraction
        FrameExtractionJob::dispatch($event->videoPath);
    }
} 