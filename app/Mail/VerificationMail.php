<?php

namespace App\Mail;

use App\Models\Setting;
use App\Models\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public int $code)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Verification Mail');
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $template_id = Setting::getValueByKey('otp_template');
        $template = Template::find($template_id);
        $content = !is_null($template) ? $template['content'] : config('mail_template.otp_code');

        $body = str_replace('{code}', $this->code, $content);

        return new Content(
            view: 'emails.otp',
            with: [
                'body' => $body,
            ],
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
