<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class VideoReportController extends Controller
{
    public function index(){
        return Inertia::render('Reports/VideoReportsOverview', [
            'videos' => Auth::user()->videos,
        ]);
    }

    public function show(Request $request, string $id){
        $report = Video::findOrFail($id);

        // Ensure the video belongs to the authenticated user
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Get all frames matching the pattern from both disks using Storage facade
        $pattern = $report->video_path . '_*.jpg';
        $original_frames = Storage::disk('frames')->files('', false);
        $visualized_frames = Storage::disk('gradcam_frames')->files('', false);

        // Filter frames matching the pattern
        $original_frames = array_filter($original_frames, function($frame) use ($pattern) {
            return fnmatch($pattern, basename($frame));
        });

        $visualized_frames = array_filter($visualized_frames, function($frame) use ($pattern) {
            return fnmatch($pattern, basename($frame));
        });

        // Sort frames to ensure they're in order
        sort($original_frames);
        sort($visualized_frames);

        // Create frame pairs for the view using the secure route
        $frames = array_map(function ($original, $visualized) use ($report) {
            return [
                'original' => route('frame.show', [
                    'video' => $report->id,
                    'type' => 'original',
                    'filename' => basename($original)
                ]),
                'visualized' => route('frame.show', [
                    'video' => $report->id,
                    'type' => 'visualized',
                    'filename' => basename($visualized)
                ])
            ];
        }, $original_frames, $visualized_frames);

        return Inertia::render('Reports/VideoReportDetail', [
            'report' => $report,
            'frames' => $frames
        ]);
    }
}
