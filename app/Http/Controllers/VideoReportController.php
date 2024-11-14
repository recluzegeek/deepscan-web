<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class VideoReportController extends Controller
{
    public function index(){
        return Inertia::render('Reports/VideoReportsOverview', [
            'videos' => Auth::user()->videos,
        ]);
    }

//    TODO: Add route for viewing singular report and make sure the report belongs to the user

    public function show(Request $request, string $id){
        $report = Video::findOrFail($id);

        return Inertia::render('Reports/VideoReportDetail', [
            'report' => $report
        ]);
    }
}
