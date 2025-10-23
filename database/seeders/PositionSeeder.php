<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Position;

class PositionSeeder extends Seeder {
    public function run(): void {
        // Position::truncate();
        $csvFile = fopen(base_path('database/data/positions.csv'), 'r');
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ',')) !== false) {
            if (! $firstline) {
                Position::create([
                    'name' => $data['0'],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
