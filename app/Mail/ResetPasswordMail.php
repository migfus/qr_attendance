<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
  use Queueable, SerializesModels;

  protected $data;

  public function __construct($email, $name, $code) {
    $this->data['email'] = $email;
    $this->data['name'] = $name;
    $this->data['code'] = $code;
  }

  public function envelope(): Envelope {
    return new Envelope(
      subject: 'Reset Password Mail',
    );
  }

  public function content(): Content {
    try {
      return new Content(
        view: 'mails.forgot',
        with: [
          'email' => $this->data['email'],
          'name'  => $this->data['name'],
          'code'  => $this->data['code'],
        ]
      );
    }
    catch(\Exception $e) {
      dd( $e);
    }
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
