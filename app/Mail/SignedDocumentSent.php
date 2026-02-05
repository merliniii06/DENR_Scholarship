<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SignedDocumentSent extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $messageText;
    public $applicationTypeName;

    /**
     * Create a new message instance.
     *
     * @param object $application Row from permit_to_study or study_leave
     * @param string $messageText Message to include in email
     * @param string $filePath Full path to the uploaded file for attachment
     * @param string $applicationTypeName e.g. 'Permit to Study' or 'Study Leave'
     */
    public function __construct($application, $messageText, $filePath, $applicationTypeName = 'Application')
    {
        $this->application = $application;
        $this->messageText = $messageText;
        $this->applicationTypeName = $applicationTypeName;
        $this->filePath = $filePath;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Signed Document - DENR Scholarship',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.signed-document-sent',
        );
    }

    public function attachments(): array
    {
        if (!isset($this->filePath) || !is_file($this->filePath)) {
            return [];
        }
        return [
            Attachment::fromPath($this->filePath)->as(basename($this->filePath)),
        ];
    }
}
