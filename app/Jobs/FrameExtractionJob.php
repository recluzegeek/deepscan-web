<?php

namespace App\Jobs;

use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Video;
use Illuminate\Support\Facades\Log;

class FrameExtractionJob implements ShouldQueue
{
    use Queueable;

    protected string $video_path;
    protected string $video_id;
    /**
     * Create a new job instance.
     */
    public function __construct(string $video_path, string $video_id)
    {
        $this->video_path = $video_path;
        $this->video_id = $video_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $video = Video::findOrFail($this->video_id);
            $video->update(['video_status' => Video::STATUS_PROCESSING]);

            FFMpeg::fromDisk('videos')
                ->open($this->video_path)
                ->exportFramesByAmount(8)
                ->toDisk('frames')
                ->save(basename($this->video_path).'_%04d.jpg');

            $video->update(['video_status' => Video::STATUS_PROCESSED]);

            // Dispatch to FastAPI for inference
            ProcessVideoJob::dispatch($video);
        } catch (\Exception $e) {
            Log::error('Frame extraction failed', [
                'video_id' => $this->video_id,
                'error' => $e->getMessage()
            ]);
            Video::where('id', $this->video_id)->update([
                'video_status' => Video::STATUS_FAILED,
                'error_message' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
