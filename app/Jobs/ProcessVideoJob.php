<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ProcessVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $video_id;
    protected $frames_path;

    public function __construct(Video $video)
    {
        $this->video_id = $video->id;
        $this->frames_path = $video->video_path;
    }

    public function handle()
    {
        try {
            $video = Video::findOrFail($this->video_id);
            $video->update(['video_status' => Video::STATUS_INFERENCING]);

            // Send request to FastAPI
            Log::info('Sending request to '.config('app.model_api_url'));
            Http::post(config('app.model_api_url'), [
                'frames_path' => $this->frames_path,
                'video_id' => $this->video_id
            ]);

        } catch (\Exception $e) {

            Log::error('Video processing failed', [
                'video_id' => $this->video_id,
                'error' => $e->getMessage()
            ]);
            
            $this->video->update([
                'video_status' => Video::STATUS_FAILED,
                'error_message' => $e->getMessage()
            ]);
        }
    }
} 