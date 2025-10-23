<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\RequestStatusType;

class RequestStatusTypeSeeder extends Seeder {
    public function run(): void {
        $data = [
            ['name' => 'Unverified', 'hero_icon_id' => 'clock_micro'], // pending (will notify thru email for PIN code)
            ['name' => 'Processing', 'hero_icon_id' => 'arrow-path_micro'], // your post was verfied and now processing (will notify thru email)
            ['name' => 'Completed',  'hero_icon_id' => 'check-circle_micro'], // you can now claim the ID (will notify thru email)
            ['name' => 'Claimed',    'hero_icon_id' => 'arrow-down-tray_micro'], // (will notify thru email using name who claim or self as a default)
            ['name' => 'Rejected',   'hero_icon_id' => 'x-mark_micro'], // (will notify thru email in case of error input)
            ['name' => 'Removed',    'hero_icon_id' => 'trash_micro'], // (will notify thru email in case of error input)
        ];

        foreach ($data as $row) {
            RequestStatusType::create($row);
        }
    }
}
