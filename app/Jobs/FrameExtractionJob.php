<?php

namespace App\Jobs;

use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Video;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


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

            FFMpeg::fromDisk('temp_videos')
                ->open($this->video_path)
                ->exportFramesByAmount(8)
                ->toDisk('temp_frames')
                ->save(basename($this->video_path).'_%04d.jpg');

            Log::info('Frames extracted for video: ' . $this->video_path);

            $video->update(['video_status' => Video::STATUS_PROCESSED]);

            // Upload the frames to MinIO
            $frames = Storage::disk('temp_frames')->files();

            foreach ($frames as $frame) {
                $framePath = Storage::disk('temp_frames')->path($frame);
                $disk = Storage::disk('minio_frames');
                $disk->putFileAs('', $framePath, basename($frame));
                // delete the local frame after uploading
                Storage::disk('temp_frames')->delete($frame);
            }

            Log::info('Frames uploaded to MinIO for video: ' . $this->video_path);

            // Delete the video upon frame extraction
            Storage::disk('temp_videos')->delete($this->video_path);

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
