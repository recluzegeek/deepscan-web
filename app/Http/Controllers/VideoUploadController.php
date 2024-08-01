<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoUploadRequest;
use App\Jobs\FrameExtractionJob;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoUploadController extends Controller
{
    public function store(VideoUploadRequest $request)
    {
        $files = $request->file('video');

        foreach ($files as $file) {

            $video_path = Storage::disk('uploaded_videos')->put('', $file);

            Video::create([
                'filename' => $file->getClientOriginalName(),
                'path' => $video_path,
                'status' => 'new',
                'user_id' => Auth::id()
            ]);

            dispatch(new FrameExtractionJob($video_path));
        }

        $request->session()->flash('files_upload_success', "Files uploaded successfully!");
    }
}
