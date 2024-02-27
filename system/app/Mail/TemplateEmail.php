<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class TemplateEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($details)
    {
        $this->details = $details;
    }


    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        $mail = $this->from($this->details['from']['email'], $this->details['from']['name'] ?? null)
        ->markdown('emails.reminder', [
            'details' => $this->details
        ])->subject($this->details['subject']);

        foreach($this->details['attachments'] ?? [] as $path) {
            $mail->attach(Attachment::fromPath($path));
        }

        if(isset($this->details['logo'])) {
            $mail->attach(Attachment::fromPath($this->details['logo'])->as('logo.png'));
        }

        return $mail;
    }
}

