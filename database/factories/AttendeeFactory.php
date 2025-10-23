<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\{Attendee, Attendance, User};

class AttendeeFactory extends Factory {
    protected $model = Attendee::class;

    public function definition(): array {
        return [
            'attendance_id' => Attendance::where('name', 'Testing Attendance')->firstOrFail()->id,
            'user_id' => User::where('email', 'admin@email.com')->firstOrFail()->id,
            'host_user_id' => User::where('email', 'admin@email.com')->firstOrFail()->id,
            'scanner_user_id' => User::where('email', 'admin@email.com')->firstOrFail()->id,
            'scanned_date_time' => Carbon::now(),
        ];
    }
}
