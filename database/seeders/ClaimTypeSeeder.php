<?php

namespace Database\Seeders;

use App\Models\ClaimType;

use Illuminate\Database\Seeder;

class ClaimTypeSeeder extends Seeder {
    public function run(): void {
        $data = [
            [
                'name' => 'ARTA ID + Lanyard',
                'price' => 200,
                'image_url' => '/images/complete-set 2025-06-26.png'
            ],
            [
                'name' => 'ARTA ID only',
                'price' => 100,
                'image_url' => '/images/id-only 2025-06-26.png'
            ],
            [
                'name' => 'Lanyard only',
                'price' => 100,
                'image_url' => '/images/lanyard-only 2025-06-26.png'
            ],
        ];

        foreach ($data as $item) {
            ClaimType::create($item);
        }
    }
}
