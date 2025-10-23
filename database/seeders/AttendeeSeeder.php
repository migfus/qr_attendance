<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{Attendance, Attendee, User};

class AttendeeSeeder extends Seeder {
    public function run(): void {
        $attendees = [
            // [
            //     'attendance_id' => Attendance::where('name', 'Testing Attendance')->first()->id,
            //     'user_id' => User::where('email', 'migfus20@gmail.com')->first()->id,
            //     'host_user_id' => User::where('email', 'migfus20@gmail.com')->first()->id,
            //     'scanner_user_id' => User::where('email', 'migfus20@gmail.com')->first()->id,
            //     'scanned_date_time' => Carbon::now(),
            // ],
            // [
            //     'attendance_id' => Attendance::where('name', 'Testing Attendance')->first()->id,
            //     'user_id' => User::where('email', 'manager@email.com')->first()->id,
            //     'host_user_id' => User::where('email', 'migfus20@gmail.com')->first()->id,
            //     'scanner_user_id' => null,
            //     'scanned_date_time' => null,
            // ],
            // [
            //     'attendance_id' => Attendance::where('name', 'Testing Attendance')->first()->id,
            //     'user_id' => User::where('email', 'scanner@email.com')->first()->id,
            //     'host_user_id' => User::where('email', 'migfus20@gmail.com')->first()->id,
            //     'scanner_user_id' => null,
            //     'scanned_date_time' => null,
            // ],
        ];

        foreach ($attendees as $row) {
            Attendee::create($row);
        }
    }
}
