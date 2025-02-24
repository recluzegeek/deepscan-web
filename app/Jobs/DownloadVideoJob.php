<?php

namespace App\Jobs;

use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;
use App\Models\Video;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DownloadVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $videoId;
    protected $filename;

    public function __construct($url, $videoId, $filename)
    {
        $this->url = $url;
        $this->videoId = $videoId;
        $this->filename = $filename;
    }

    public function handle()
    {
        try {
            $video = Video::findOrFail($this->videoId);
            $video->update(['video_status' => Video::STATUS_DOWNLOADING]);

            Log::info('Downloading video '.$video->video_path.' from url '.$this->url);

            // Initialize YoutubeDl
            $yt = new YoutubeDl();
            $collection = $yt->download(
                Options::create()
                    ->downloadPath(storage_path('app/videos'))
                    ->url($this->url)
            );

            foreach ($collection->getVideos() as $downloadedVideo) {
                if ($downloadedVideo->getError() !== null) {
                    echo "Error downloading video: {$downloadedVideo->getError()}.";
                } else {
                    echo $downloadedVideo->getTitle(); // Will return Phonebloks
                    $downloadedFile = $downloadedVideo->getFile(); // \SplFileInfo instance of downloaded file
                    $videoPath = $downloadedFile->getPathname();

                    // Move the downloaded file to the configured disk
                    $disk = Storage::disk('videos');
                    $diskPath = $disk->putFileAs($downloadedFile, $this->filename);

                    // Update video record with download success and path
                    $video->update([
                        'video_status' => Video::STATUS_DOWNLOADED,
                        'video_path' => $diskPath
                    ]);

                    Log::info('+++++Stored the downloaded video to: '. $diskPath. ' +++++');
                }
            }

            // After successful download, queue for frame extraction
            FrameExtractionJob::dispatch($video->video_path, $this->videoId);

        } catch (\Exception $e) {
            Log::error('Download job failed', [
                'video_id' => $this->videoId,
                'url' => $this->url,
                'error' => $e->getMessage()
            ]);
            Video::where('id', $this->videoId)->update([
                'video_status' => Video::STATUS_FAILED,
                'error_message' => $e->getMessage()
            ]);
            throw $e;
        }
    }
} 