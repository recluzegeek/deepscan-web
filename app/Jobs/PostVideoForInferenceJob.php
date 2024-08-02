<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class PostVideoForInferenceJob implements ShouldQueue
{
    use Queueable;

    protected string $frames_path;

    /**
     * Create a new job instance.
     */
    public function __construct(string $frames_path)
    {
        $this->frames_path = $frames_path;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $model_api_url = config('app.model_api_url');

        Http::post($model_api_url, [
            'frames_path' => $this->frames_path
        ]);
    }
}
