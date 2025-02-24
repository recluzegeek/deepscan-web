<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\VideoDownloaded;
use App\Listeners\ProcessVideo;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        VideoDownloaded::class => [
            ProcessVideo::class,
        ],
    ];
} 