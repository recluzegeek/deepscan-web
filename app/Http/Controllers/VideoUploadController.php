<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoUploadRequest;
use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoUploadController extends Controller
{
    public function store(VideoUploadRequest $request)
    {

        $video_path = Storage::disk('local')->put('videos' , $request->file('video'));

        Video::create([
            'filename' => $request->file('video')->getClientOriginalName(),
            'path' => $video_path,
            'status' => 'new',
            'user_id' => Auth::id()
        ]);
    }
}
