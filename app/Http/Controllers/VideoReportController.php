<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class VideoReportController extends Controller
{
    function index(){
        return Inertia::render('Reports/VideoReports', [
            'videos' => Auth::user()->videos,
        ]);
    }
}
