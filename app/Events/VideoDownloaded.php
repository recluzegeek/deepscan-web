<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoDownloaded
{
    use Dispatchable, SerializesModels;

    public string $videoPath;

    /**
     * Create a new event instance.
     *
     * @param string $videoPath
     */
    public function __construct(string $videoPath)
    {
        $this->videoPath = $videoPath;
    }
} 