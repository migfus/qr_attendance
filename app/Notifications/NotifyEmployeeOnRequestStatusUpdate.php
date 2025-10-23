<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyEmployeeOnRequestStatusUpdate extends Notification {
    use Queueable;

    protected $employee_id;
    protected $name;
    protected $content;
    protected $request_status_type;

    public function __construct(string $employee_id, string $name, string $content, string $request_status_type) {
        $this->employee_id = $employee_id;
        $this->name = $name;
        $this->content = $content;
        $this->request_status_type = $request_status_type;
    }

    public function via(): array {
        return ['mail'];
    }

    public function toMail(): MailMessage {
        return (new MailMessage)
            ->subject(($this->request_status_type == 'Unverified' ? 'Pending/Unveirifed' : $this->request_status_type) . ' - ARTA ID Request')
            ->greeting('Hello ' . $this->name . '.')
            ->line(strip_tags($this->content))
            // ->line('It seems there\'s an update. Request is now ' . $this->request_status_type)
            ->action('Check Request Status', url('/arta-id/' . $this->employee_id))
            ->line('You can check the status by clicking the "Check Request Status"')
            ->line('Thank you for using the OHRM Attendance.');
    }
}
