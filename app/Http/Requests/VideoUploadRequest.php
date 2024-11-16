<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'video' => 'required|array',
            'video.*' => 'required|file|mimetypes:video/mp4,video/x-matroska,video/x-msvideo,video/x-flv|max:20480',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'video.required' => 'Please upload at least one video file.',
            'video.array' => 'Invalid upload format.',
            'video.*.mimetypes' => 'Video is not a valid video format (MP4, MKV, AVI, FLV).',
            'video.*.max' => 'Video exceed the 20MB size limit.',
        ];
    }
}
