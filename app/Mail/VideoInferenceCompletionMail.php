<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VideoInferenceCompletionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private string $video_id;
    private string $name;
    private string $result;
    private float $probability;
    private string $filename;
    private string $uploaded_on;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Video $video)
    {
        $this->name = $user->name;
        $this->video_id = $video->id;
        $this->filename = $video->filename;
        $this->result = $video->predicted_class;
        $this->probability = number_format($video->prediction_probability * 100, 2, '.', '');
        $this->uploaded_on = Carbon::parse($video->created_at)->diffForHumans();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Video Detection Results Are Ready!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.video-inference',
            with: [
                'name' => $this->name,
                'filename' => $this->filename,
                'result' => $this->result,
                'probability' => $this->probability,
                'uploaded_on' => $this->uploaded_on,
                'video_id' => $this->video_id
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
