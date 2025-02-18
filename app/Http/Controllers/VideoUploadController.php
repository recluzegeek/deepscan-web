<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoUploadRequest;
use App\Jobs\FrameExtractionJob;
use App\Jobs\PostVideoForInferenceJob;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class VideoUploadController extends Controller
{
    public function store(VideoUploadRequest $request)
    {
        $files = $request->file('video');

        foreach ($files as $file) {

            $video_path = Storage::disk('uploaded_videos')->put('', $file);

            $video = Video::create([
                'filename' => $file->getClientOriginalName(),
                'video_path' => $video_path,
                'video_status' => 'queued',
                'user_id' => Auth::id()
            ]);

            // Chain the jobs
            Bus::chain([
                new FrameExtractionJob($video_path),
                new PostVideoForInferenceJob($video_path, $video->id),
            ])->onQueue('deepscan_model')->dispatch();

        }

        $request->session()->flash('files_upload_success', "Files uploaded successfully!");
    }
}
