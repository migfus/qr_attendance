<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
  use Queueable;

  protected string $code;
  protected string $name;
  protected string $email;

  public function __construct($email, $name, $code) {
    $this->email = $email;
    $this->name  = $name;
    $this->code  = $code;
  }

  public function via(object $notifiable): array {
    return ['mail'];
  }

  public function toMail(object $notifiable): MailMessage {
    return (new MailMessage)
      ->line('The introduction to the notification.')
      ->action('Notification Action', url('/'))
      ->line('Thank you for using our application!');
  }

  public function toArray(object $notifiable): array {
    return [
      //
    ];
  }
}
