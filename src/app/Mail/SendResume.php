<?php

namespace App\Mail;

use App\Models\Resume;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class SendResume extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Resume $resume, public $lang = 'en', public $style = 'default')
    {
        app()->setLocale($this->lang);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        app()->setLocale($this->lang);
        return new Envelope(
            subject: __('Your resume is ready!'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        app()->setLocale($this->lang);
        return new Content(
            view: 'mail.resume',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $filename = 'resume';
        if ($this->resume->template->name) {
            $filename .= '-' . strtolower($this->resume->template->name);
        }
        if ($this->resume->style != '') {
            $filename .= '-' . $this->resume->style;
        }
        $filename .= '.pdf';
        return [
            Attachment::fromPath($this->resume->getResumePDFFilePath($this->style))

                ->as($filename)

                ->withMime('application/pdf')
        ];
    }
}
