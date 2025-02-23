<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoUploadRequest;
use App\Jobs\DownloadVideoJob;
use App\Jobs\FrameExtractionJob;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoProcessingController extends Controller
{
    public function upload(VideoUploadRequest $request)
    {
        $files = $request->file('video');

        foreach ($files as $file) {
            $video_path = Storage::disk('uploaded_videos')->put('', $file);

            // Create a new video record
            $video = Video::create([
                'filename' => $file->getClientOriginalName(),
                'video_path' => $video_path,
                'video_status' => Video::STATUS_DOWNLOADED,
                'user_id' => Auth::id()
            ]);

            // Dispatch the frame extraction job
            FrameExtractionJob::dispatch($video_path, $video->id, 'uploaded_videos');
        }

        $request->session()->flash('files_upload_success', "Files uploaded successfully!");
    }

    public function download(Request $request)
    {
        $request->validate([
            'urls' => 'required|array',
            'urls.*' => 'required|string',
        ]);

        $urls = $request->input('urls');
        $results = [];

        foreach ($urls as $url) {
            // Check if the URL is from YouTube
            if (!preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/', $url)) {
                $results[] = [
                    'url' => $url,
                    'status' => 'error',
                    'message' => 'Unsupported platform. Only YouTube URLs are supported.'
                ];
                continue;
            }

            // Create initial video record for YouTube download
            $filename = parse_url($url, PHP_URL_QUERY);
            $filename = str_replace('v=', '', $filename); // Remove 'v=' from the start of the video ID
            $video = Video::create([
                'video_status' => Video::STATUS_QUEUED,
                'user_id' => Auth::id(),
                'filename' => $filename,
                'video_path' => Storage::disk('downloaded_videos')->put('', $filename)
            ]);

            // Queue the YouTube download job
            DownloadVideoJob::dispatch($url, $video->id, $filename);

            $results[] = [
                'url' => $url,
                'status' => Video::STATUS_QUEUED,
                'video_id' => $video->id
            ];
        }

        return response()->json([
            'message' => 'Video download requests have been queued.',
            'results' => $results
        ], 200);
    }
} 