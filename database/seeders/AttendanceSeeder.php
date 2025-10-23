<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

use App\Models\{Attendance, User};

class AttendanceSeeder extends Seeder {
    public function run(): void {
        $attendances = [
            [
                'name' => 'Testing Attendance',
                'author_id' => User::where('email', 'migfus20@gmail.com')->first()->id,
                'description' => 'Attendance for the College of Arts and Sciences Seminar on "The Impact of AI on Higher Education"',
                'start_date_time' => Carbon::now(),
                'end_date_time' => '2025-02-02 12:00:00',
                'forced_closed_date_time' => '2025-02-03 00:00:00',
                'department_id' => 1,
            ],

        ];

        foreach ($attendances as $row) {
            Attendance::create($row);
        }
    }
}
