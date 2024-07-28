<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasUuids;

    protected $table = 'uploaded_videos';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'filename',
        'path',
        'status',
        'user_id'
    ];

    protected $hidden = [
      'user_id'
    ];
}
