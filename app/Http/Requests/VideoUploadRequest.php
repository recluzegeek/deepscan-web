<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
//            'video' => 'required|mimetypes:video/mp4,video/x-msvideo,video/x-matroska,video/x-flv|max:10240',
            'video' => 'required',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'video.mimetypes' => 'The uploaded file must be a valid video format (MP4, AVI, MKV, FLV).',
            'video.required' => 'Please upload a video file.',
            'video.max' => 'The video file must not exceed 10 MB.',
        ];
    }
}
