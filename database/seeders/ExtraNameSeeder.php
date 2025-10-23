<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\ExtraName;

class ExtraNameSeeder extends Seeder {
    public function run(): void {
        $data = [
            ['name' => 'N/A'],
            ['name' => 'JR'],
            ['name' => 'SR'],
            ['name' => 'II'],
            ['name' => 'III'],
            ['name' => 'IV'],
            ['name' => 'V'],
            ['name' => 'VI'],
            ['name' => 'VII'],
            ['name' => 'VIII'],
            ['name' => 'IX'],
            ['name' => 'X'],
        ];

        foreach ($data as $row) {
            ExtraName::create($row);
        }
    }
}
