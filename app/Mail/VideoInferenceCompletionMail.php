<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VideoInferenceCompletionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    private $name;
    private $result;
    private $probability;
    private $filename;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $fileame, $result, $probability)
    {
        $this->name = $name;
        $this->result = $result;
        $this->probability = $probability;
        $this->filename = $fileame;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Video Inference Completion Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.video-inference',
            with: ['name' => $this->name,  'filename' => $this->filename,'result' => $this->result, 'probability' => $this->probability]
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
