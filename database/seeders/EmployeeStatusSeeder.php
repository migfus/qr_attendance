<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\EmployeeStatus;

class EmployeeStatusSeeder extends Seeder
{
    public function run(): void {
        $data = [
            ['name' => 'Permanent'],
            ['name' => 'Job Order'],
            ['name' => 'COS - Contract of Service'],
            ['name' => 'Temporary'],
            ['name' => 'Contractual'],
            ['name' => 'CoTerminous'],
            // ['name' => 'Elective'], // removed
            ['name' => 'Part-Time'],
            ['name' => 'Substitute'],
            ['name' => 'Casual'],
        ];

        foreach($data as $row) {
            EmployeeStatus::create($row);
        }
    }
}
