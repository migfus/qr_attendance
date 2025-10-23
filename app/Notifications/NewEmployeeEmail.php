<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEmployeeEmail extends Notification {
    use Queueable;

    protected $pin_code;
    protected $email;
    protected $employee_id;
    protected $name;
    protected $type;

    public function __construct(string $id, string $email, string $pin_code, string $last_name, string $first_name, string $type) {
        $this->employee_id = $id;
        $this->email = $email;
        $this->pin_code = $pin_code;
        $this->name = $last_name . ', ' . $first_name;
        $this->type = $type;
    }

    public function via(): array {
        return ['mail'];
    }

    public function toMail(): MailMessage {
        return (new MailMessage)
            ->subject($this->type == 'Unverified' ? '🕑 Pending/Unverified' : $this->type  . ' - ARTA ID Request')
            ->greeting('Hello ' . $this->name . '.')
            ->line('It appears that you have requested an ARTA ID.')
            ->line('You can check the status using the link below.')
            ->line('PIN Code: ' . $this->pin_code)
            ->action('Check Request Status', url('/arta-id/' . $this->employee_id))
            // ->action('Edit Request', url('/arta-id/' . $this->employee_id . '/edit?pin=' . $this->pin_code))
            ->line('If you\'re planning to change the information, make sure that is currently pending. \n This is automation message, please do not reply.');
    }
}
