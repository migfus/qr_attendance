<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            HeroIconSeeder::class,
            LaratrustSeeder::class,
            SystemSettingsSeeder::class,
            //   GroupSeeder::class,
            //   GroupPermissionSeeder::class,
            //   GroupRoleSeeder::class,
            DepartmentSeeder::class,
            EmployeeStatusSeeder::class,
            UserSeeder::class,
            // QuickResponseSeeder::class,
            FeedbackTypeSeeder::class,
            FeedbackSeeder::class,

            AttendanceSeeder::class,
            AttendeeSeeder::class,

            ExtraNameSeeder::class,
            PositionSeeder::class,
            ClaimTypeSeeder::class,
            RequestStatusTypeSeeder::class,
            FileTypeSeeder::class,

            // EmployeeSeeder::class,
        ]);
    }
}
