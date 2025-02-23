<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasUuids;

    const STATUS_QUEUED = 'queued';           // Initial state when video is created
    const STATUS_DOWNLOADING = 'downloading';  // Video is being downloaded
    const STATUS_DOWNLOADED = 'downloaded';    // Download completed
    const STATUS_PROCESSING = 'processing';    // Frames are being extracted
    const STATUS_PROCESSED = 'processed';      // Frames extracted, ready for inference
    const STATUS_INFERENCING = 'inferencing';  // Being processed by FastAPI
    const STATUS_COMPLETED = 'completed';      // All processing done
    const STATUS_FAILED = 'failed';           // Error occurred

    protected $table = 'videos';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'filename',
        'video_path',
        'video_status',
        'user_id',
        'predicted_class',
        'prediction_probability'
    ];

    protected $hidden = [
      'user_id',
        'video_path'
    ];
}
