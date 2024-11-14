<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasUuids;

    protected $table = 'videos';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'filename',
        'video_path',
        'video_status',
        'user_id'
    ];

    protected $hidden = [
      'user_id',
        'video_path'
    ];
}
