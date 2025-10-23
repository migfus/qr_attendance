<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{Notification, NotificationType, User};

class NotificationSeeder extends Seeder {
    public function run(): void {
        $data = [
            [
                'user_id' => User::where('email', 'admin@email.com')->first()->id,
                'notification_type_id' => NotificationType::where('name', 'System')->first()->id,
                'title' => 'Welcome',
                'content' => 'Welcome New User'
            ]
        ];

        foreach ($data  as $row) {
            Notification::create($row);
        }
    }
}
