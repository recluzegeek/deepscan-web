<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Http\Request;

class VideoReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'prediction', 'period']);

        return Inertia::render('Reports/VideoReportsOverview', [
            'filters' => $filters,
            'videos' => Auth::user()->videos()
                ->when($filters['status'] ?? null, fn($query, $status) =>
                    $query->where('video_status', $status)
                )
                ->when($filters['prediction'] ?? null, fn($query, $prediction) =>
                    $query->where('predicted_class', $prediction)
                )
                ->when($filters['period'] ?? null, function($query, $period) {
                    return match($period) {
                        'today' => $query->whereDate('created_at', today()),
                        'week' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
                        'month' => $query->whereMonth('created_at', now()->month),
                        default => $query
                    };
                })
                ->orderBy('created_at', 'desc')
                ->paginate(8)
                ->through(fn ($video) => [
                    'id' => $video->id,
                    'filename' => $video->filename,
                    'video_status' => $video->video_status,
                    'predicted_class' => $video->predicted_class,
                    'prediction_probability' => $video->prediction_probability,
                    'created_at' => $video->created_at
                ])
                ->withQueryString()
        ]);
    }

    public function show(Request $request, string $id)
    {
        $video = Video::findOrFail($id);

        if ($video->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Add queue position for queued videos
        $queuePosition = null;
        $estimatedWaitTime = null;
        if ($video->video_status === 'queued') {
            $queuePosition = Video::where('video_status', 'queued')
                ->where('created_at', '<', $video->created_at)
                ->count() + 1;
            $estimatedWaitTime = $this->calculateEstimatedWaitTime($queuePosition);
        }

        return Inertia::render('Reports/VideoReportDetail', [
            'report' => array_merge($video->toArray(), [
                'queue_position' => $queuePosition,
                'estimated_wait_time' => $estimatedWaitTime,
            ]),
            'frames' => $video->video_status === 'completed' ? $this->getFramePairs($video) : []
        ]);
    }

    private function getFramePairs(Video $video)
    {
        // Only attempt to get frames if video processing is completed
        if ($video->video_status !== 'completed') {
            return [];
        }

        try {
            $pattern = $video->video_path . '_*.jpg';
            $original_frames = Storage::disk('frames')->files('', false);
            $visualized_frames = Storage::disk('gradcam_frames')->files('', false);

            // Filter frames matching the pattern
            $original_frames = array_filter($original_frames, function($frame) use ($pattern) {
                return fnmatch($pattern, basename($frame));
            });

            $visualized_frames = array_filter($visualized_frames, function($frame) use ($pattern) {
                return fnmatch($pattern, basename($frame));
            });

            // If no frames found, return empty array
            if (empty($original_frames) || empty($visualized_frames)) {
                return [];
            }

            // Sort frames to ensure they're in order
            sort($original_frames);
            sort($visualized_frames);

            // Create frame pairs for the view
            return array_map(function ($original, $visualized) use ($video) {
                return [
                    'original' => route('frame.show', [
                        'video' => $video->id,
                        'type' => 'original',
                        'filename' => basename($original)
                    ]),
                    'visualized' => route('frame.show', [
                        'video' => $video->id,
                        'type' => 'visualized',
                        'filename' => basename($visualized)
                    ])
                ];
            }, $original_frames, $visualized_frames);

        } catch (\Exception $e) {
            // Log the error but don't expose it to the user
            \Log::error('Error getting frame pairs: ' . $e->getMessage());
            return [];
        }
    }

    private function calculateEstimatedWaitTime($queuePosition)
    {
        // Assuming each video takes approximately 2 minutes to process
        $minutesPerVideo = 2;
        $totalMinutes = $queuePosition * $minutesPerVideo;

        if ($totalMinutes < 60) {
            return $totalMinutes . ' minutes';
        }

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        if ($hours === 1) {
            return "1 hour " . ($minutes > 0 ? "and $minutes minutes" : '');
        }

        return "$hours hours " . ($minutes > 0 ? "and $minutes minutes" : '');
    }
}
