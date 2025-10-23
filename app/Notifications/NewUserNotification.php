<?php

namespace App\Notifications;

use App\Models\GuestQrCode;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification {
    use Queueable;

    protected $newQRCode;

    public function __construct(GuestQrCode $newQRCode) {
        $this->newQRCode = $newQRCode;
    }

    public function via(object $notifiable): array {
        // return ['mail', 'database'];
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage {
        return (new MailMessage)
            ->greeting('Hello Fucker!')
            ->line('A new QR has registered: ' . $this->newQRCode->name)
            ->action('View Dashboard', url('/dashboard/qr/' . $this->newQRCode->id))
            ->line('You can check it later');
    }

    public function toDatabase() {
        return [
            'message' => $this->newQRCode->name . ' has registered.',
            'url' => url('/dashboard/qr/' . $this->newQRCode->id . '/edit')
        ];
    }
}
