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
    protected $video_path;
    public $timeout = 300; // 5 minutes timeout
    public $tries = 1; // No retries, we'll handle failures manually

    public function __construct(Video $video)
    {
        $this->video_id = $video->id;
        $this->video_path = $video->video_path;
    }

    public function handle()
    {
        // try {
            // Check if FastAPI is currently processing another video
            // if (Cache::get('fastapi_processing')) {
            //     // If FastAPI is busy, requeue this job with a delay
            //     $this->release(30); // Wait 30 seconds before trying again
            //     return;
            // }

            // Mark FastAPI as busy
            // Cache::put('fastapi_processing', true, 300); // Lock for 5 minutes max

            // $this->video->update(['video_status' => Video::STATUS_INFERENCING]);

            // Send request to FastAPI
            Log::info('Sending request to '.config('app.model_api_url'));
            $response = Http::post(config('app.model_api_url'), [
                'frames_path' => $this->video_path,
                'video_id' => $this->video_id
            ]);

            if (!$response->successful()) {
                throw new \Exception('FastAPI request failed: ' . $response->body());
            }

            $result = $response->json();

            // Update video with classification results
            $this->video->update([
                'predicted_class' => $result['details']['classification'],
                'prediction_probability' => $result['details']['probability'],
                'video_status' => Video::STATUS_COMPLETED
            ]);

            // Release the FastAPI lock
            // Cache::forget('fastapi_processing');

            // Check for next video in queue and process it
            // $nextVideo = Video::where('video_status', Video::STATUS_PROCESSED)
            //     ->where('id', '!=', $this->video_id)
            //     ->orderBy('created_at')
            //     ->first();

            // if ($nextVideo) {
            //     ProcessVideoJob::dispatch($nextVideo);
            // }

        // } catch (\Exception $e) {
        //     // Release the FastAPI lock in case of error
        //     Cache::forget('fastapi_processing');

        //     Log::error('Video processing failed', [
        //         'video_id' => $this->video_id,
        //         'error' => $e->getMessage()
        //     ]);
            
        //     $this->video->update([
        //         'video_status' => Video::STATUS_FAILED,
        //         'error_message' => $e->getMessage()
        //     ]);

        //     // Check for next video even if current one failed
        //     $nextVideo = Video::where('video_status', Video::STATUS_PROCESSED)
        //         ->where('id', '!=', $this->video_id)
        //         ->orderBy('created_at')
        //         ->first();

        //     if ($nextVideo) {
        //         ProcessVideoJob::dispatch($nextVideo);
        //     }

        //     throw $e;
        // }
    }
} 