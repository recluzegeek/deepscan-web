<?php

namespace App\Jobs;

use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FrameExtractionJob implements ShouldQueue
{
    use Queueable;

    protected string $video_path;

    /**
     * Create a new job instance.
     */
    public function __construct(string $video_path)
    {
        $this->video_path = $video_path;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        FFMpeg::fromDisk('uploaded_videos')
            ->open($this->video_path)
            ->exportFramesByAmount(30)
            ->toDisk('frames')
            ->save(basename($this->video_path).'_%04d.jpg');
    }
}
