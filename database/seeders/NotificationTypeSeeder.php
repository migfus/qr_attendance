<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\NotificationType;

class NotificationTypeSeeder extends Seeder {
    public function run(): void {
        $data = [
            ['name' => 'Announcement'],
            ['name' => 'Message'],
            ['name' => 'Feedback'],
            ['name' => 'System'],
            ['name' => 'QR'],
            ['name' => 'Attendance'],
        ];

        foreach ($data as $row) {
            NotificationType::create($row);
        }
    }
}
