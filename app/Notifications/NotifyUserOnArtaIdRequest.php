<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyUserOnArtaIdRequest extends Notification {
    use Queueable;

    protected $employee_name;
    protected $employee_id;

    public function __construct(string $employee_id, string $employee_name) {
        $this->employee_id = $employee_id;
        $this->employee_name = $employee_name;
    }

    public function via(object $notifiable): array {
        // return ['mail', 'database'];
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toDatabase() {
        return [
            'message' => $this->employee_name . ' requested an Arta ID.',
            'url' => url('/dashboard/arta-id/' . $this->employee_id)
        ];
    }
}
